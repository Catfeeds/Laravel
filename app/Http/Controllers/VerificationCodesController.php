<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class VerificationCodesController extends Controller
{
    /**
     * @param Request $request
     * @param EasySms $easySms
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     */
    public function store(Request $request, EasySms $easySms)
    {
        // 验证手机号
        $this->validate($request, [
            'phone' => 'required|phone:CN'
        ], [
            'phone' => '手机号格式不合法'
        ]);

        // 一分钟发一次
        $verifyData = \Cache::get($request->phone);
        if ($verifyData && $verifyData['send_at']->getTimestamp() + 60 > time()) {
            throw new TooManyRequestsHttpException(
                $verifyData['send_at']->getTimestamp() + 60 - time(),
                '一分钟只能发送一次验证码'
            );
        }

        // 生成6位随机数，左侧补0
        $code = str_pad(random_int(1, 999999), 6, 0, STR_PAD_LEFT);
        $phone = $request->phone;
//        try {
//            $easySms->send($phone, [
//                'data'     => [
//                    'code' => $code,
//                    'time' => 10
//                ]
//            ]);
//        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
//            $message = $exception->getException('submail')->getMessage(); // submail的错误信息
//            return $this->response->errorInternal('发送短信失败');
//        }

        // 缓存验证码，10分钟过期
        $sendAt = now();
        $expiredAt = now()->addMinutes(10);
        \Cache::put($phone, ['phone' => $phone, 'code' => $code, 'send_at' => $sendAt], $expiredAt);

        return $this->response->array([
            'expired_at' => $expiredAt->toDateTimeString(),
            'code'       => $code
        ])->setStatusCode(201);
    }
}
