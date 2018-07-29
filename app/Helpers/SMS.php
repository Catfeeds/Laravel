<?php

namespace App\Helpers;

/**
 * 发送短信
 */
class SMS
{
    /**
     * 发送验证码
     * @param $phone string 手机号
     * @param $code string 验证码
     * @param $time integer 过期时间
     * @return bool
     * @throws InternalErrorException
     */
    public static function code($phone, $code, $time = 10)
    {
        $project = "t2I513";
        $vars = json_encode(['code' => $code, 'time' => $time]);
        return self::send($project, $phone, $vars);
    }

    /**
     * 调用接口，发送信息
     * @param $project
     * @param $phone
     * @param $vars
     * @throws
     * @return bool
     */
    private static function send($project, $phone, $vars)
    {
        $url = "https://api.mysubmail.com/message/xsend.json";
        $params = [
            "appid"     => env('SUBMAIL_APP_ID'),
            "signature" => env('SUBMAIL_APP_KEY'),
            "to"        => $phone,
            "project"   => $project,
            "vars"      => $vars
        ];

        $res = json_decode(self::post($url, $params), true);

        if ($res['status'] != 'success'){
            throw new \Exception($res['msg']);
        }

        return true;
    }

    /**
     * post请求
     * @param $url
     * @param $params
     * @return mixed
     */
    private static function post($url, $params)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);//POST数据
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_error($ch);
        }
        curl_close($ch);
        return $output;
    }
}