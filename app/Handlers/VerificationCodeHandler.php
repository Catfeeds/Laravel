<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/17
 * Time: 上午9:57
 */

namespace App\Handlers;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class VerificationCodeHandler
{
    /**
     * 检查验证码是否正确
     * @param $phone
     * @throws BadRequestHttpException
     * @return bool
     */
    public function validateCode($phone, $code) {
        $verifyData = \Cache::get($phone);
        if (!$verifyData) {
            throw new BadRequestHttpException(__('The validation code is expired'));
        }
        if (!hash_equals($verifyData['code'], $code)) {
            // 返回400
            throw new BadRequestHttpException(__('Wrong validation code'));
        }
        // 清除验证码缓存
        \Cache::forget($phone);
        return true;
    }
}