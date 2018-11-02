<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/21
 * Time: 上午11:02
 */

namespace App\Services;


use App\Models\User;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UsersService
{
    // 检查手机号是否已被注册：一个手机号可以注册两个类型的用户
    public function isPhoneRegistered($phone, $type)
    {
        return User::where([
            'phone' => $phone,
            'type'  => $type
        ])->exists();
    }

    // 检查邮箱是否已被绑定（注册）：一个邮箱只能绑定一个用户
    public function isEmailBound($email)
    {
        return User::where([
            'email' => $email
        ])->exists();
    }

    // 生成默认头像
    public function defaultAvatar($name)
    {
        $folder_name = "uploads/images/avatars/default";
        $upload_path = public_path() . '/' . $folder_name;
        $filename = str_random(10) . '.png';

        if(!is_dir($upload_path)) { mkdir($upload_path, 0777, true); }

        $fontSize = 20;
        $width = 40;
        $height = 40;

        $text = mb_substr($name, 0, 1);
        if(preg_match("/^[a-z]*$/i", $text)) {
            $text = strtoupper($text);
        }

        $image = Image::canvas($width, $height, '#cccccc')
            ->text($text, $width / 2, $height / 2, function ($font) use ($fontSize) {
                $font->file(public_path('font/msyh.ttf'));
                $font->align('center');
                $font->valign('middle');
                $font->size($fontSize);
                $font->color('#ffffff');
            });
        $image->save($upload_path . '/' . $filename);
        return config('app.url') . "/$folder_name/$filename";
    }
}