<?php

namespace App\Http\Controllers;
use App\Models\EmailToken;
use App\Models\User;
use App\Services\UsersService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserEmailController extends Controller
{
    // 发送一封激活邮件
    public function send(Request $request, UsersService $usersService) {

        $this->validate($request, ['email' => 'required|string|email']);

        if($usersService->isEmailBound($request->email)) {
            return $this->response->errorBadRequest(__('该邮箱已被绑定'));
        }

        $user = $this->user();

        // 一个用户只能有一条有效token。先清空历史token
        EmailToken::where('user_id', $user->id)->delete();

        $emailToken = new EmailToken();
        $emailToken->user_id = $user->id;
        $emailToken->email = $request->email;

        $token = bcrypt($request->email . time());
        $emailToken->token = $token;
        $emailToken->save();

        \Mail::send('emails.activate', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email)->subject('【Yogooooo】Activation Email');
        });

        return $this->response->noContent();
    }

    // 点击激活邮件，绑定邮箱
    public function activate(Request $request) {
        $emailToken = EmailToken::where('token', $request->token)->first();
        if(!$emailToken || $emailToken->updated_at->addDay() < Carbon::now()) {
            return 'The activation link is invalid or expired 激活链接无效或已过期';
        }

        // 如果已经被绑定了
        if((new UsersService())->isEmailBound($emailToken->email)) {
            return __('Invalid activation link: this email has been bound 激活链接无效：该邮箱已被绑定');
        }

        // 更新该用户的邮箱
        User::where('id', $emailToken->user_id)->update(['email' => $emailToken->email]);

        // 删除该用户的所有token
        EmailToken::where('user_id', $emailToken->user_id)->delete();

        return 'Successfully activate. Now you can use your email to log in. 激活成功，现在你可以使用邮箱登录';
    }
}
