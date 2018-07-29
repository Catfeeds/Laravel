<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Overtrue\EasySms\EasySms;

class UserController extends Controller
{
    public function store(UserRequest $request){
        $verifyData = \Cache::get($request->phone);
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }
        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            return $this->response->errorUnauthorized('验证码错误');
        }
        $user = User::create([
            'name' => $request->name,
            'type' => $request->type,
            'phone' =>$request->phone,
            'password' => bcrypt($request->password),
        ]);
        // 清除验证码缓存
        \Cache::forget($request->phone);
        //return $this->response->created();  TODO
//        return $this->response->item($user, )
//            ->setMeta([
//                'access_token' => \Auth::guard('api')->fromUser($user),
//                'token_type' => 'Bearer',
//                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
//            ])
//            ->setStatusCode(201);
    }
}
