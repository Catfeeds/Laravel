<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Follow;
use App\Models\Image;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
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

    public function store(UserRequest $request)
    {
        if (User::where('phone', $request->phone)->first()) {
            throw new ConflictHttpException(__('The phone number has been registered'));
        }
        $verifyData = \Cache::get($request->phone);
        if (!$verifyData) {
            return $this->response->error(__('The validation code is expired'), 422);
        }
        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            return $this->response->errorBadRequest(__('Wrong validation code'));
        }
        $user = User::create([
            'name'     => $request->name,
            'type'     => $request->type,
            'phone'    => $request->phone,
            'password' => bcrypt($request->password),
        ]);
        // 清除验证码缓存
        \Cache::forget($request->phone);
        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'token'      => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL()
            ])
            ->setStatusCode(201);
    }

    public function update(UserRequest $request)
    {
        $user = $this->user();
        $attributes = $request->only(['name', 'title', 'introduction']);
        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);
            if (!$image) {
                return $this->response()->errorNotFound('图片id不存在');
            }
            $attributes['avatar_url'] = $image->path;
        }
        $user->update($attributes);
        return $this->response->item($user, new UserTransformer());
    }

    public function me()
    {
        return $this->response->item($this->user(), new UserTransformer());
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

    public function following(User $user)
    {
        $currentUser = $this->user();
        $following = $user->followings()->paginate(20);
        $following->each(function ($user) use ($currentUser) {
            $user->setFollowing($currentUser);
        });
        return $this->response->paginator($following, new UserTransformer());
    }

    public function follower(User $user)
    {
        $currentUser = $this->user();
        $follower = $user->followers()->paginate(20);
        $follower->each(function ($user) use ($currentUser) {
            $user->setFollowing($currentUser);
        });
        return $this->response->paginator($follower, new UserTransformer());
    }

    public function recommend()
    {
        $currentUser = $this->user();
        $users = User::where('type', 'designer')
            ->whereDoesntHave('followers', function ($query) use ($currentUser) {
                $query->where('follower_id', $currentUser->id);
            })
            ->limit(10)
            ->get();
        return $this->response->collection($users, new UserTransformer());
    }
}
