<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/29
 * Time: 上午12:52
 */


$sms = app('easysms'); // app辅助函数，获取App实例
try {
    $sms->send(15650753237, [
        'template' => 't2I513', // 模板id
        'data'    => [
            'code' => 123123,
            'time' => 5
        ]
    ]);
} catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
    $message = $exception->getException('submail')->getMessage(); // submail的错误信息
    dd($message); // 格式化打印辅助函数
}