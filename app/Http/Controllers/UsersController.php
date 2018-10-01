<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\CheckPhoneRequest;
use App\Http\Requests\SearchUsersRequest;
use App\Services\UserMailsService;
use App\Services\UsersService;
use App\Services\VerificationCodesService;
use App\Http\Requests\UserRequest;
use App\Models\Follow;
use App\Models\Upload;
use App\Models\User;
use App\Transformers\CurrentUserTransformer;
use App\Transformers\UserForReviewTransformer;
use App\Transformers\UserTransformer;
use App\Transformers\UserWithCanReviewTransformer;
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
        $attributes = $request->only(['name', 'title', 'introduction', 'id_number', 'bank_name', 'bank_card_number', 'account_name', 'qualification_urls', 'professional_fields', 'avatar_url']);

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

        // 通过审核后不能修改身份信息
        if ($user->review_status == 1 && ($request->id_number || $request->id_card_id)) {
            throw new BadRequestHttpException(__('认证信息只能设置一次，不能再次更改'));
        }
        if ($user->review_status != 1 && $request->id_card_id) {
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

    public function applyReview()
    {
        $user = $this->user();
        if ($user->review_status == 1) {
            $this->response->errorBadRequest(__('您已通过审核'));
        }
        $user->review_status = 0;
        $user->save();
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
        $currentUser = \Auth::guard('api')->user();

        if (!$currentUser) {
            if ($user->type != 'designer') {
                $this->response->errorUnauthorized(); // 如果要查看甲方的信息，需要登录后再确认
            }
        } else {
            $this->authorizeForUser($currentUser, 'retrieve', $user);
        }

        $user->increment('views'); // 浏览量+1
        $user->setFollowing($this->user()); // 是否关注
        $user->setCanReview($this->user()); // 是否可以评价
        return $this->response->item($user, new UserWithCanReviewTransformer());
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

    // 推荐用户：甲方优先推荐以前合作过但是没关注的；设计师就推荐没关注的
    public function recommend()
    {
        $currentUser = $this->user();

        // 如果当前用户是甲方，优先推荐以前合作过但是没关注的设计师
        if($currentUser->type === 'party') {
            // 先获取合作过但是没关注的设计师
            $users = User::where('type', 'designer')
                ->whereHas('payments.project.user', function ($query) use ($currentUser) {
                    $query->where('user_id', $currentUser->id);
                })
                ->whereDoesntHave('followers', function ($query) use ($currentUser) {
                    $query->where('follower_id', $currentUser->id);
                })
                ->limit(10)
                ->inRandomOrder()
                ->get();

            if ($users->count() < 20) {
                $others = User::where('type', 'designer')
                    ->whereNotIn('id', $users->pluck('id'))
                    ->limit(10 - ($users->count()))
                    ->inRandomOrder()
                    ->get();
                $users = $users->concat($others);
            }
        }

        // 如果当前用户是设计师，推荐Ta没关注的设计师
        else {
            $users = User::where('type', 'designer')
                ->where('id', '!=', $currentUser->id)
                ->whereDoesntHave('followers', function ($query) use ($currentUser) {
                    $query->where('follower_id', $currentUser->id);
                })
                ->limit(10)
                ->inRandomOrder()
                ->get();
        }

        return $this->response->collection($users, new UserTransformer());
    }

    // 搜索：只能搜索设计师
    public function search(SearchUsersRequest $request)
    {
        if ($request->invite_to_review) {
            return $this->searchToInviteReview($request);
        }

        $currentUser = $this->user();

        $query = User::where('name', 'like', "%$request->keyword%")
            ->where('type', 'designer');

        if (is_array($request->professional_fields)) {
            $fields = $request->professional_fields;
            $query->where(function ($query) use ($fields) {
                foreach ($fields as $field) {
                    $query->orWhereJsonContains('professional_fields', $field);
                }
            });
        }

        // 按照完成项目个数降序：收到一份设计费代表完成一个项目
        if($request->order === 'completed_project_count_desc') {
            $query->withCount('payments')->orderBy('payments_count', 'desc');
        }

        $users = $query->paginate(20);
        $users->each(function ($user) use ($currentUser) {
            $user->setFollowing($currentUser);
        });

        return $this->response->paginator($users, new UserTransformer());
    }

    // 如果搜索用户是为了邀请用户评价，则不显示当前登录用户，并设置'review_status'属性
    public function searchToInviteReview(SearchUsersRequest $request)
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
            $user->setReviewStatusToUser($currentUser); // 搜索到的用户对当前用户的评价状态
        });

        return $this->response->paginator($users, new UserForReviewTransformer());
    }
}
