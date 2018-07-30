<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {
    // 短信验证码
    $api->post('verificationCode', 'VerificationCodeController@store')
        ->name('verificationCode.store');
    // 用户注册
    $api->post('users', 'UserController@store')
        ->name('user.store');

    // 需要 token 验证的接口
    $api->group(['middleware' => 'api.auth'], function($api) {
        // 当前登录用户信息
        $api->get('user', 'UserController@me')
            ->name('user.show');
    });
});