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
    public function sendActivationMail(User $user)
    {
        $emailToken = EmailToken::where([
            'user_id' => $user->id,
            'email'   => $user->email
        ])->first();

        if (!$emailToken) {
            $emailToken = new EmailToken();
            $emailToken->user_id = $user->id;
            $emailToken->email = $user->email;
        }

        $token = bcrypt($user->email . time());
        $emailToken->token = $token;
        $emailToken->save();

        $user->notifyViaEmail(new ActivateEmail($token), false);
    }
}