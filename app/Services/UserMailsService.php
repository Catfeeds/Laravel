<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/18
 * Time: 下午5:54
 */

namespace App\Services;


use App\Models\EmailToken;
use App\Models\User;
use App\Notifications\ActivateEmail;

class UserMailsService
{
    // 发送激活邮件
    public function sendActivationMail(User $user) {
        $token = bcrypt($user->email.time());
        $emailToken = EmailToken::firstOrCreate(['email' => $user->email]);
        $emailToken->update([ 'token' => $token ]);
        $user->notifyViaEmail(new ActivateEmail($token), false);
    }
}