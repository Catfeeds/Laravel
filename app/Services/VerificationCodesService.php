<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/17
 * Time: 上午9:57
 */

namespace App\Services;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class VerificationCodesService
{
    /**
     * 检查验证码是否正确
     * @param $key string 可以是phone或email
     * @throws BadRequestHttpException
     * @return bool
     */
    public function validateCode($key, $code) {
        $verifyData = \Cache::get($key);
        if (!$verifyData) {
            throw new BadRequestHttpException(__('验证码已失效'));
        }
        if (!hash_equals($verifyData['code'], $code)) {
            // 返回400
            throw new BadRequestHttpException(__('验证码错误'));
        }
        // 清除验证码缓存
        \Cache::forget($key);
        return true;
    }
}