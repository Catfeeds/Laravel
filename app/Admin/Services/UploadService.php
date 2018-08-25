<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/25
 * Time: 上午11:55
 */

namespace App\Admin\Services;


class UploadService
{
    // 根据以/public/uploads为根目录的相对路径，获取完整的url链接
    public static function getFullUrlByPath($path) {
        return env('APP_URL') . '/uploads/' . $path;
    }
}