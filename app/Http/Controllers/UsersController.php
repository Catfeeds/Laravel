<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Services\UserMailsService;
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
    public function checkPhone(Request $request)
    {
        if (User::where('phone', $request->phone)->first()) {
            throw new ConflictHttpException(__('The phone number has been registered'));
        } else {
            return $this->response->noContent();
        }
    }

    public function store(UserRequest $request, VerificationCodesService $service)
    {
        if (User::where('phone', $request->phone)->first()) {
            throw new ConflictHttpException(__('The phone number has been registered'));
        }

        // 检验验证码
        $service->validateCode($request->phone, $request->code);

        $user = User::create([
            'name'     => $request->name,
            'type'     => $request->type,
            'phone'    => $request->phone,
            'password' => bcrypt($request->password),
        ]);
        return $this->response->item($user, new CurrentUserTransformer())
            ->setMeta([
                'token'      => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL()
            ])
            ->setStatusCode(201);
    }

    public function update(UserRequest $request, UserMailsService $mailsService)
    {
        $user = $this->user();
        $attributes = $request->only(['name', 'title', 'introduction', 'company_name', 'id_number', 'registration_number']);

        if ($request->avatar_id) {
            $attributes['avatar_url'] = Upload::find($request->avatar_id)->path;
        }

        // 更改邮箱时，发送激活邮件
        $needSendMail = false;
        if ($request->email && $request->email != $user->email) {
            // 检测是否重复
            if (User::where('email', $request->email)->exists()) {
                return $this->response->errorBadRequest(__('The email address has been bound'));
            }
            $attributes['email'] = $request->email;
            $attributes['email_activated'] = false;
            $needSendMail = true;
        }

        // 认证信息只能填写一次
        if ($request->company_name && $user->company_name) {
            throw new BadRequestHttpException(__('Can only set authentication information once'));
        }
        if ($request->registration_number && $user->registration_number) {
            throw new BadRequestHttpException(__('Can only set authentication information once'));
        }
        if ($request->id_number && $user->id_number) {
            throw new BadRequestHttpException(__('Can only set authentication information once'));
        }
        if ($request->business_license_id) {
            if ($user->business_license_url) {
                throw new BadRequestHttpException(__('Can only set authentication information once'));
            }
            $attributes['business_license_url'] = Upload::find($request->business_license_id)->path;
        }
        if ($request->id_card_id) {
            if ($user->id_card_url) {
                throw new BadRequestHttpException(__('Can only set authentication information once'));
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
        if ($request->type) {
            $query = $query->where('type', $request->type);
        }
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
        if($request->invite_to_review) {
            return $this->searchToInviteReview($request);
        }

        $currentUser = $this->user();
        $query = User::where('name', 'like', "%$request->keyword%");

        if ($request->type) {
            $query = $query->where('type', $request->type);
        }
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
        $query = User::where('name', 'like', "%$request->keyword%");

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
