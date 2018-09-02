<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerificationCodeRequest;
use App\Models\User;
use App\Services\UsersService;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{
    // 发送验证码，接收方式为手机短信或邮件，优先手机短信
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $this->checkAvailable($request);

        // 6位随机数，左侧补0
        $code = str_pad(random_int(1, 999999), 6, 0, STR_PAD_LEFT);

        if($request->phone) {
            $this->sendViaPhone($request->phone, $code, $easySms);
        } else {
            $this->sendViaEmail($request->email, $code);
        }

        // 缓存验证码，10分钟过期
        $sendAt = now();
        $expiredAt = now()->addMinutes(10);
        \Cache::put($request->phone ?? $request->email, ['code' => $code, 'send_at' => $sendAt], $expiredAt);

        return $this->response->array([
            'expired_at' => $expiredAt->toDateTimeString(),
//            'code'       => $code
            'code'       => 'xxxxxx'
        ])->setStatusCode(201);
    }

    // 检测是否允许发送验证码，不允许时返回异常响应
    private function checkAvailable(Request $request)
    {
        $service = new UsersService;

        if ($request->phone) {
            $isRegistered = $service->isPhoneRegistered($request->phone, $request->user_type);
        } else {
            $isRegistered = $service->isEmailBound($request->email);
        }

        if ($request->action_type === 'resetPassword' && !$isRegistered) {
            $this->response->errorNotFound(
                $request->phone ?
                    __('该手机号未注册') :
                    __('该邮箱未注册'));
        } else if ($request->action_type != 'resetPassword' && $isRegistered) {
            $this->response->error($request->phone ?
                __('该手机号已被注册') :
                __('该邮箱已被注册'), 409);
        }
    }

    private function sendViaPhone($phone, $code, EasySms $easySms) {
        try {
            $easySms->send($phone, [
                'data' => [
                    'code' => $code,
                    'time' => 10
                ]
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
            $message = $exception->getException('submail')->getMessage(); // submail的错误信息
            $this->response->errorInternal('发送短信失败: ' . $message);
        } catch (\Overtrue\EasySms\Exceptions\InvalidArgumentException $exception) {
            $message = $exception->getMessage();
            $this->response->errorInternal('发送短信失败: ' . $message);
        }
    }

    private function sendViaEmail($email, $code) {
        \Mail::raw("验证码: $code", function ($message) use ($email) {
            $message ->to($email)->subject('测试邮件');
        });
    }
}
