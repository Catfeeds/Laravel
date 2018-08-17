<?php

namespace App\Http\Controllers;

use App\Handlers\VerificationCodeHandler;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangePhoneRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * 用户认证信息：密码、邮箱
 * Class UserAuthsController
 * @package App\Http\Controllers
 */
class UserPasswordController extends Controller
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

    public function changePhone (ChangePhoneRequest $request, VerificationCodeHandler $handler) {
        // 检查是否被注册
        if (User::where('phone', $request->phone)->first()) {
            throw new ConflictHttpException(__('The phone number has been registered'));
        }

        $handler->validateCode($request->phone, $request->code);

        // 更新手机号
        $this->user()->update([
            'phone' => $request->phone
        ]);
        return $this->response->noContent();
    }

    public function resetPassword(ResetPasswordRequest $request, VerificationCodeHandler $handler) {
        $user = User::where('phone', $request->phone)->first();
        if(!$user) {
            return $this->response->errorNotFound(__('The phone number is not registered'));
        }

        $handler->validateCode($request->phone, $request->code);

        $user->update([
            'password' => bcrypt($request->password)
        ]);
        return $this->response->noContent();
    }
}
