<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/30
 * Time: 上午9:28
 */

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizationRequest;
use App\Transformers\CurrentUserTransformer;
use App\Transformers\UserTransformer;
use \Tymon\JWTAuth\Facades\JWTAuth;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
        // TODO 邮箱也能登录 见教程4.5
//        filter_var($username, FILTER_VALIDATE_EMAIL) ?
//            $credentials['email'] = $username :
//            $credentials['phone'] = $username;

        $credentials = $request->only(['phone', 'password', 'type']);
        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorBadRequest(__('Wrong phone number or password'));
        }
        JWTAuth::setToken($token);
        $user = JWTAuth::toUser($token);
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