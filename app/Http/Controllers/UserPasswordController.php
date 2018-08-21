<?php

namespace App\Http\Controllers;

use App\Services\UsersService;
use App\Services\VerificationCodesService;
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
        if (!\Auth::guard('api')->attempt([
            'phone' => $currentUser->phone,
            'type' => $currentUser->type,
            'password' => $request->password
        ])) {
            return $this->response->errorBadRequest(__('Wrong original password'));
        }
        $currentUser->update([
            'password' => bcrypt($request->new_password)
        ]);
        return $this->response->noContent();
    }

    public function changePhone (ChangePhoneRequest $request, VerificationCodesService $service, UsersService $usersService) {
        $currentUser =  $this->user();

        // 检查是否被注册
        if ($usersService->isPhoneRegistered($request->phone, $currentUser->type)) {
            throw new ConflictHttpException(__('The phone number has been registered'));
        }

        $service->validateCode($request->phone, $request->code);

        // 更新手机号
        $currentUser->update([
            'phone' => $request->phone
        ]);
        return $this->response->noContent();
    }

    public function resetPassword(ResetPasswordRequest $request, VerificationCodesService $service) {
        $user = User::where([
            'phone' => $request->phone,
            'type' => $request->type
        ])->first();

        if(!$user) {
            return $this->response->errorNotFound(__('The phone number is not registered'));
        }

        $service->validateCode($request->phone, $request->code);

        $user->update([
            'password' => bcrypt($request->password)
        ]);
        return $this->response->noContent();
    }
}
