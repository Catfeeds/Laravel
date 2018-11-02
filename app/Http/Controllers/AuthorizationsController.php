<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/30
 * Time: 上午9:28
 */

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizationRequest;
use App\Models\User;
use App\Transformers\CurrentUserTransformer;
use App\Transformers\UserTransformer;
use \Tymon\JWTAuth\Facades\JWTAuth;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
        $credentials = $request->only(['password', 'type']);

        if(filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ) {
            $credentials['email'] = $request->identifier;
        } else {
            $credentials['phone'] = $request->identifier;
        }

        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorBadRequest(__('用户名或密码错误'));
        }

        JWTAuth::setToken($token);
        $user = JWTAuth::toUser($token);

        // 检查用户是否可以登录
        $this->authorizeForUser($user, 'login', User::class);
//        $user->can('login', User::class);

        return $this->response->item($user, new CurrentUserTransformer())
            ->setMeta([
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL()
            ]);
    }

    public function update()
    {
        $token = \Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        \Auth::guard('api')->logout();
        return $this->response->noContent();
    }

    protected function respondWithToken($token)
    {
        return $this->response->array([
            'token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => \Auth::guard('api')->factory()->getTTL() // 一个月过期
        ]);
    }
}