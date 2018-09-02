<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\CheckPhoneRequest;
use App\Services\UserMailsService;
use App\Services\UsersService;
use App\Services\VerificationCodesService;
use App\Http\Requests\UserRequest;
use App\Models\Follow;
use App\Models\Upload;
use App\Models\User;
use App\Transformers\CurrentUserTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UsersController extends Controller
{
    // 检查手机号是否被注册
    public function checkPhone(CheckPhoneRequest $request, UsersService $service)
    {
        if ($service->isPhoneRegistered($request->phone, $request->type)) {
            throw new ConflictHttpException(__('该手机号已被注册'));
        }
        return $this->response->noContent();
    }

    // 注册：可以用手机号或者邮箱注册
    public function store(UserRequest $request, VerificationCodesService $service, UsersService $usersService)
    {
        if ($request->phone && $usersService->isPhoneRegistered($request->phone, $request->type)) {
            throw new ConflictHttpException(__('该手机号已被注册'));
        }
        if ($request->email && $usersService->isEmailBound($request->email)) {
            throw new ConflictHttpException(__('该邮箱已被注册'));
        }

        // 检验验证码
        $service->validateCode($request->phone ?? $request->email, $request->verification_code);

        $user = User::create([
            'name'            => $request->name,
            'type'            => $request->type,
            'phone'           => $request->phone,
            'email'           => $request->email,
            'email_activated' => $request->email ? true : false,
            'avatar_url'      => $usersService->defaultAvatar($request->name),
            'password'        => bcrypt($request->password),
        ]);

        return $this->response->item($user, new CurrentUserTransformer())
            ->setMeta([
                'token'      => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL()
            ])
            ->setStatusCode(201);
    }

    public function update(UserRequest $request, UserMailsService $mailsService, UsersService $usersService)
    {
        $user = $this->user();
        $attributes = $request->only(['name', 'title', 'introduction', 'id_number', 'bank_name', 'bank_card_number', 'account_name', 'qualification_urls']);

        if ($request->avatar_id) {
            $attributes['avatar_url'] = Upload::find($request->avatar_id)->path;
        }

        // 更改邮箱时，发送激活邮件
        $needSendMail = false;
        if ($request->email && $request->email != $user->email) {
            if (!$user->phone) {
                $this->response->errorBadRequest(__('您还未绑定手机号，无法更改绑定邮箱'));
            }

            if ($usersService->isEmailBound($request->email)) {
                $this->response->errorBadRequest(__('该邮箱已被绑定'));
            }
            $attributes['email'] = $request->email;
            $attributes['email_activated'] = false;
            $needSendMail = true;
        }

        // 身份信息只能填写一次
        if ($request->id_number && $user->id_number) {
            throw new BadRequestHttpException(__('认证信息只能设置一次，不能再次更改'));
        }
        if ($request->id_card_id) {
            if ($user->id_card_url) {
                throw new BadRequestHttpException(__('认证信息只能设置一次，不能再次更改'));
            }
            $attributes['id_card_url'] = Upload::find($request->id_card_id)->path;
        }

        $user->update($attributes);

        // 发送激活邮件
        if ($needSendMail) {
            $mailsService->sendActivationMail($user);
        }

        return $this->response->item($user, new CurrentUserTransformer());
    }

    // 当前登录用户
    public function me()
    {
        return $this->response->item($this->user(), new CurrentUserTransformer());
    }

    // 某个用户
    public function index(User $user)
    {
        $user->setFollowing($this->user());
        return $this->response->item($user, new UserTransformer());
    }

    public function follow(User $user)
    {
        if ($user->type === 'party') {
            return $this->response->errorBadRequest('只能关注设计师');
        }

        $currentUser = $this->user();
        if ($currentUser->id == $user->id) {
            return $this->response->errorBadRequest();
        }
        if (!Follow::where([
            'follower_id' => $currentUser->id,
            'user_id'     => $user->id
        ])->exists()) {
            $currentUser->increment('following_count');
            $user->increment('follower_count');
            $currentUser->followings()->syncWithoutDetaching([$user->id]);
        }
        return $this->response->noContent();
    }

    public function unfollow(User $user)
    {
        $currentUser = $this->user();
        if (Follow::where([
            'follower_id' => $currentUser->id,
            'user_id'     => $user->id
        ])->exists()) {
            $currentUser->decrement('following_count');
            $user->decrement('follower_count');
            $currentUser->followings()->detach([$user->id]);
        }
        return $this->response->noContent();
    }

    // 某个用户关注的人
    public function following(Request $request, User $user)
    {
        $currentUser = $this->user();
        $query = $user->followings();
        $users = $query->paginate(20);
        $users->each(function ($user) use ($currentUser) {
            $user->setFollowing($currentUser);
        });
        return $this->response->paginator($users, new UserTransformer());
    }

    // 某个用户的粉丝
    public function follower(Request $request, User $user)
    {
        $currentUser = $this->user();
        $query = $user->followers();
        if ($request->type) {
            $query = $query->where('type', $request->type);
        }
        $users = $query->paginate(20);
        $users->each(function ($user) use ($currentUser) {
            $user->setFollowing($currentUser);
        });
        return $this->response->paginator($users, new UserTransformer());
    }

    public function recommend()
    {
        $currentUser = $this->user();
        $users = User::where('type', 'designer')
            ->where('id', '!=', $currentUser->id)
            ->whereDoesntHave('followers', function ($query) use ($currentUser) {
                $query->where('follower_id', $currentUser->id);
            })
            ->limit(10)
            ->get();
        return $this->response->collection($users, new UserTransformer());
    }

    public function search(Request $request)
    {
        if ($request->invite_to_review) {
            return $this->searchToInviteReview($request);
        }

        $currentUser = $this->user();
        $query = User::where('name', 'like', "%$request->keyword%")
            ->where('type', 'designer'); // 只能搜索设计师

//        if ($request->type) {
//        $query = $query->where('type', 'designer');
//        }
        $users = $query->paginate(20);
        $users->each(function ($user) use ($currentUser) {
            $user->setFollowing($currentUser);
        });

        return $this->response->paginator($users, new UserTransformer());
    }

    // 如果搜索用户是为了邀请用户评价，则不显示当前登录用户，并设置'review_status'属性
    public function searchToInviteReview(Request $request)
    {
        $currentUser = $this->user();
        $query = User::where('name', 'like', "%$request->keyword%")
            ->where('type', 'designer'); // 只能搜索设计师

        if ($currentUser) {
            $query = $query->where('id', '!=', $currentUser->id);
        }

        $users = $query->paginate(20);
        $users->each(function ($user) use ($currentUser) {
            $user->setFollowing($currentUser);
            $user->setReviewStatus($currentUser);
        });

        return $this->response->paginator($users, new UserTransformer());
    }
}
