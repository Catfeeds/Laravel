<?php

namespace App\Http\Controllers;

use App\Http\Requests\MailRequest;
use App\Models\EmailToken;
use App\Models\User;
use App\Notifications\ActivateEmail;
use App\Services\UserMailsService;
use App\Services\UsersService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;

class UserEmailController extends Controller
{
    // 发送一封激活邮件
    public function send(UserMailsService $mailsService) {
        $user = $this->user();
        if (!$user->email) {
            return $this->response->errorBadRequest(__('该用户还未设置邮箱'));
        }
        $mailsService->sendActivationMail($user);
        return $this->response->noContent();
    }

    // 点击激活邮件，绑定邮箱
    // 未被激活绑定邮箱可以被多个用户填写，谁先激活就是谁的
    public function activate(Request $request) {
        $emailToken = EmailToken::where('token', $request->token)->first();
        if(!$emailToken || $emailToken->updated_at->addDay() < Carbon::now()) {
            return '激活链接无效或已过期';
        }

        // 如果已经被绑定了
        if((new UsersService())->isEmailBound($emailToken->email)) {
            return __('激活链接无效：该邮箱已被绑定');
        }

        // 更新该用户的邮箱
        User::where('id', $emailToken->user_id)->update([
            'email' => $emailToken->email,
            'email_activated' => true
        ]);

        $emailToken->delete(); // 删除使用过的token

        return '激活成功';
    }
}
