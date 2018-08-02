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
    'namespace'  => 'App\Http\Controllers',
    'middleware' => ['serializer:array', 'bindings']
], function ($api) {
    // 检查手机号是否被注册
    $api->post('checkPhone/{phone}', 'UsersController@checkPhone')
        ->name('api.users.checkPhone');
    // 短信验证码
    $api->group([
        'middleware' => 'api.throttle',
        'limit'      => 2,
        'expires'    => 1
    ], function ($api) {
        $api->post('verificationCode', 'VerificationCodesController@store')
            ->name('api.verificationCode.store');
    });
    // 用户注册
    $api->post('users', 'UsersController@store')
        ->name('api.users.store');

    // 登录
    $api->post('authorizations', 'AuthorizationsController@store')
        ->name('api.authorizations.store');
    // 刷新token
    $api->put('authorizations/current', 'AuthorizationsController@update')
        ->name('api.authorizations.update');
    // 删除token
    $api->delete('authorizations/current', 'AuthorizationsController@destroy')
        ->name('api.authorizations.destroy');

    // 某个用户发布的动态
    $api->get('users/{user}/activities', 'ActivitiesController@userIndex')
        ->name('api.users.activities.index');
    // 动态回复列表
    $api->get('activities/{activity}/replies', 'RepliesController@index')
        ->name('api.activities.replies.index');

    // 需要 token 验证的接口
    $api->group(['middleware' => 'api.auth'], function ($api) {
        // 当前登录用户信息
        $api->get('user', 'UsersController@me')
            ->name('api.user.show');
        // 编辑登录用户信息
        $api->patch('user', 'UsersController@update')
            ->name('api.user.update');

        // 关注一名用户
        $api->put('user/following/{user}', 'UsersController@follow')
            ->name('api.user.follow');
        // 取消关注
        $api->delete('user/following/{user}', 'UsersController@unfollow')
            ->name('api.user.unfollow');

        // 图片资源
        $api->post('images', 'ImagesController@store')
            ->name('api.images.store');

        // 发布动态
        $api->post('activities', 'ActivitiesController@store')
            ->name('api.activities.store');
        // 删除动态
        $api->delete('activities/{activity}', 'ActivitiesController@destroy')
            ->name('api.activities.delete');
        // 首页：关注的人动态
        $api->get('activities/feeds', 'ActivitiesController@feeds')
            ->name('api.activities.feeds');

        // 评论一条动态
        $api->post('activities/{activity}/replies', 'RepliesController@store')
            ->name('api.activities.replies.store');
        // 删除一条评论
        $api->delete('activities/{activity}/replies/{reply}', 'RepliesController@destroy')
            ->name('api.activities.replies.destroy');
        // 当前用户发表的评论
        $api->get('user/replies', 'RepliesController@userIndex')
            ->name('api.user.replies.index');


        // 通知列表
        $api->get('user/notifications', 'NotificationsController@index')
            ->name('api.user.notifications.index');
        // 通知统计
        $api->get('user/notifications/stats', 'NotificationsController@stats')
            ->name('api.user.notifications.stats');
        // 标记所有通知为已读
        $api->patch('user/read/notifications', 'NotificationsController@readAll')
            ->name('api.user.notifications.readAll');
        // 标记单个通知为已读
        $api->put('user/read/notifications/{notification_id}', 'NotificationsController@readOne')
            ->name('api.user.notifications.readOne');
    });
});