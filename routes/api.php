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
    'middleware' => ['serializer:array', 'bindings']
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

    // 某个用户发布的动态
    $api->get('users/{user}/activities', 'ActivityController@userIndex')
        ->name('api.users.activities.index');

    // 需要 token 验证的接口
    $api->group(['middleware' => 'api.auth'], function($api) {
        // 当前登录用户信息
        $api->get('user', 'UserController@me')
            ->name('api.user.show');
        // 编辑登录用户信息
        $api->patch('user', 'UserController@update')
            ->name('api.user.update');

        // 关注一名用户
        $api->put('user/following/{user}', 'UserController@follow')
            ->name('api.user.follow');
        // 取消关注
        $api->delete('user/following/{user}', 'UserController@unfollow')
            ->name('api.user.unfollow');

        // 图片资源
        $api->post('images', 'ImageController@store')
            ->name('api.images.store');

        // 发布动态
        $api->post('activities', 'ActivityController@store')
            ->name('api.activities.store');
        // 删除动态
        $api->delete('activities/{activity}', 'ActivityController@destroy')
            ->name('api.activities.delete');
        // 首页：关注的人动态
        $api->get('activities/feeds', 'ActivityController@feeds')
            ->name('api.activities.feeds');

        // 评论一条动态
        $api->post('activities/{activity}/replies', 'ReplyController@store')
            ->name('api.activities.replies.store');
        // 删除一条评论
        $api->delete('activities/{activity}/replies/{reply}', 'ReplyController@destroy')
            ->name('api.activities.replies.destroy');
    });
});