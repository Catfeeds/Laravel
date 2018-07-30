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
$api->version('v1', [
    'namespace' => 'App\Http\Controllers',
    'middleware' => 'serializer:array'
], function ($api) {
    // 短信验证码
    $api->post('verificationCode', 'VerificationCodeController@store')
        ->name('api.verificationCode.store');
    // 用户注册
    $api->post('users', 'UserController@store')
        ->name('api.users.store');

    // 登录
    $api->post('authorizations', 'AuthorizationController@store')
        ->name('api.authorizations.store');
    // 刷新token
    $api->put('authorizations/current', 'AuthorizationController@update')
        ->name('api.authorizations.update');
    // 删除token
    $api->delete('authorizations/current', 'AuthorizationController@destroy')
        ->name('api.authorizations.destroy');

    // 需要 token 验证的接口
    $api->group(['middleware' => 'api.auth'], function($api) {
        // 当前登录用户信息
        $api->get('user', 'UserController@me')
            ->name('api.user.show');
        // 编辑登录用户信息
        $api->patch('user', 'UserController@update')
            ->name('api.user.update');
        // 图片资源
        $api->post('images', 'ImageController@store')
            ->name('api.images.store');
        // 发布动态
        $api->post('activities', 'ActivityController@store')
            ->name('api.activities.store');
    });
});