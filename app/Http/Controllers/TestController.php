<?php
/**
 * User: ZhuKaihao
 * Date: 2018/9/1
 * Time: 下午9:12
 */

namespace App\Http\Controllers;


use App\Handlers\ImageUploadHandler;

class TestController extends Controller
{
    public function test(){
        return (new ImageUploadHandler)->defaultAvatar('朱');
    }
}