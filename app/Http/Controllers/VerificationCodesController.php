<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerificationCodeRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        // 重置密码：
        if ($request->type === 'resetPassword' && !User::where('phone', $request->phone)->exists()) {
            return $this->response->errorNotFound(__('The phone number is not registered'));
        } else if (User::where('phone', $request->phone)->exists()) {
            return $this->response->errorNotFound(__('The phone number has been registered'));
        }

        // 生成6位随机数，左侧补0
        $code = str_pad(random_int(1, 999999), 6, 0, STR_PAD_LEFT);
        $phone = $request->phone;
        try {
            $easySms->send($phone, [
                'data' => [
                    'code' => $code,
                    'time' => 10
                ]
            ]);
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
            $message = $exception->getException('submail')->getMessage(); // submail的错误信息
            return $this->response->errorInternal('发送短信失败');
        }

        // 缓存验证码，10分钟过期
        $sendAt = now();
        $expiredAt = now()->addMinutes(10);
        \Cache::put($phone, ['phone' => $phone, 'code' => $code, 'send_at' => $sendAt], $expiredAt);

        return $this->response->array([
            'expired_at' => $expiredAt->toDateTimeString(),
//            'code'       => $code
            'code'       => 'xxxxxx'
        ])->setStatusCode(201);
    }
}
