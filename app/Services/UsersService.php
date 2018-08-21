<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/21
 * Time: 上午11:02
 */

namespace App\Services;


use App\Models\User;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UsersService
{
    // 检查手机号是否已被注册：一个手机号可以注册两个类型的用户
    public function isPhoneRegistered($phone, $type)
    {
        return User::where([
            'phone' => $phone,
            'type'  => $type
        ])->exists();
    }
}