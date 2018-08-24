<?php

namespace App\Http\Controllers;

use App\Http\Requests\MailRequest;
use App\Models\EmailToken;
use App\Models\User;
use App\Notifications\ActivateEmail;
use App\Services\UserMailsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;

class UserEmailController extends Controller
{
    // 发送一封激活邮件
    public function send(UserMailsService $mailsService) {
        $user = $this->user();
        if (!$user->email) {
            return $this->response->errorBadRequest(__('该用户还未绑定邮箱'));
        }
        $mailsService->sendActivationMail($user);
        return $this->response->noContent();
    }

    // 激活邮件
    public function activate(Request $request) {
        $emailToken = EmailToken::where('token', $request->token)->first();
        if(!$emailToken || $emailToken->updated_at->addDay() < Carbon::now()) {
            return '激活链接无效或已过期';
        }
        User::where('email', $emailToken->email)->update([
            'email_activated' => true
        ]);
        $emailToken->delete(); // 删除使用过的token
        return '激活成功';
    }
}