<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangePhoneRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserAuthsController extends Controller
{
    public function changePassword(ChangePasswordRequest $request) {
        $currentUser = $this->user();
        $credentials['phone'] = $currentUser->phone;
        $credentials['password'] = $request->password;
        if (!\Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorBadRequest(__('Wrong original password'));
        }
        $currentUser->update([
            'password' => bcrypt($request->new_password)
        ]);
        return $this->response->noContent();
    }

    public function changePhone (ChangePhoneRequest $request) {
        $verifyData = \Cache::get($request->phone);
        if (!$verifyData) {
            return $this->response->error(__('The validation code is expired'), 422);
        }
        if (!hash_equals($verifyData['code'], $request->code)) {
            // 返回401
            return $this->response->errorBadRequest(__('Wrong validation code'));
        }
        // 清除验证码缓存
        \Cache::forget($request->phone);
        // 检查是否被注册
        if (User::where('phone', $request->phone)->first()) {
            throw new ConflictHttpException(__('The phone number has been registered'));
        }
        // 更新手机号
        $this->user()->update([
            'phone' => $request->phone
        ]);
        return $this->response->noContent();
    }

    public function resetPassword(ResetPasswordRequest $request) {
        $user = User::where('phone', $request->phone)->first();
        if(!$user) {
            return $this->response->errorNotFound(__('The phone number is not registered'));
        }

        $verifyData = \Cache::get($request->phone);
        if (!$verifyData) {
            return $this->response->error(__('The validation code is expired'), 422);
        }
        if (!hash_equals($verifyData['code'], $request->code)) {
            // 返回401
            return $this->response->errorBadRequest(__('Wrong validation code'));
        }
        // 清除验证码缓存
        \Cache::forget($request->phone);

        $user->update([
            'password' => bcrypt($request->password)
        ]);
        return $this->response->noContent();
    }
}
