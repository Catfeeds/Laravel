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
    'middleware' => ['serializer:array', 'bindings', 'change-locale']
], function ($api) {
    /**
     * 账号相关
     */
    // 检查手机号是否被注册
    $api->post('users/check', 'UsersController@checkPhone')
        ->name('api.users.checkPhone');
    // 短信验证码
    $api->group([
        'middleware' => 'api.throttle',
        'limit'      => 5, // 一分钟只能发送两条短信
        'expires'    => 1
    ], function ($api) {
        $api->post('verificationCode', 'VerificationCodesController@store')
            ->name('api.verificationCode.store');
    });
    $api->group([
        'middleware' => 'api.throttle',
        'limit'      => 5,
        'expires'    => 1
    ], function ($api) {
        // 重置密码
        $api->patch('users/reset', 'UserPasswordController@resetPassword')
            ->name('api.users.resetPassword');
    });
    // 激活邮箱
    $api->get('/activate_email', 'UserEmailController@activate')
        ->name('api.user.email.activate');
    // 注册账号
    $api->post('users', 'UsersController@store')
        ->name('api.users.store');

    /**
     * 首页展示内容
     */
    // 设计师列表
    $api->get('/index/designers', 'IndexController@designers')
        ->name('api.index.designers');
    // 作品列表
    $api->get('/index/works', 'IndexController@works')
        ->name('api.index.works');
    // 项目列表
    $api->get('/index/projects', 'IndexController@projects')
        ->name('api.index.projects');

    /**
     * Token相关
     */
    // 登录
    $api->post('authorizations', 'AuthorizationsController@store')
        ->name('api.authorizations.store');
    // 刷新token
    $api->put('authorizations/current', 'AuthorizationsController@update')
        ->name('api.authorizations.update');
    // 删除token
    $api->delete('authorizations/current', 'AuthorizationsController@destroy')
        ->name('api.authorizations.destroy');

    /**
     * 用户信息相关
     */
    // 搜索用户
    $api->get('users', 'UsersController@search')
        ->name('api.users.search');
    // 获取某名用户的基本信息
    $api->get('users/{user}', 'UsersController@index')
        ->where('user', '[0-9]+')
        ->name('api.users.index');

    /**
     * 动态相关
     */
    // 获取一条动态
    $api->get('activities/{activity}', 'ActivitiesController@index')
        ->where('activity', '[0-9]+')
        ->name('api.activities.index');
    // 某个用户发布的动态
    $api->get('users/{user}/activities', 'ActivitiesController@userIndex')
        ->where('user', '[0-9]+')
        ->name('api.users.activities.index');
    // 动态回复列表
    $api->get('activities/{activity}/replies', 'RepliesController@index')
        ->where('user', '[0-9]+')
        ->name('api.activities.replies.index');
    // 热门动态
    $api->get('activities/trending', 'ActivitiesController@trending')
        ->name('api.activities.trending');

    /**
     * 关注列表相关
     */
    // 某个用户关注的人
    $api->get('users/{user}/following', 'UsersController@following')
        ->where('user', '[0-9]+')
        ->name('api.users.following');
    // 某个用户的粉丝
    $api->get('users/{user}/followers', 'UsersController@follower')
        ->where('user', '[0-9]+')
        ->name('api.users.follower');

    /**
     * 评价相关
     */
    // 某个用户收到的评价
    $api->get('users/{user}/reviews', 'UserReviewsController@index')
        ->where('user', '[0-9]+')
        ->name('api.users.reviews.index');

    /**
     * 项目相关
     */
    // 某个业主发布的项目
    $api->get('users/{user}/projects', 'ProjectsController@partyIndex')
        ->where('user', '[0-9]+')
        ->name('api.users.projects.partyIndex');
    // 搜索项目
    $api->get('projects', 'ProjectsController@search')
        ->name('api.projects.search');

    /**
     * 作品相关
     */
    // 获取某个用户的作品集
    $api->get('users/{user}/works', 'WorksController@userIndex')
        ->name('api.users.works.userIndex');
    // 获取所有用户的作品集
    $api->get('works', 'WorksController@index')
        ->name('api.users.works.userIndex');

    /**
     * 需要 token 验证的接口
     */
    $api->group(['middleware' => 'api.auth'], function ($api) {
        /**
         * 用户信息相关
         */
        // 当前登录用户信息
        $api->get('user', 'UsersController@me')
            ->name('api.user.show');
        // 编辑登录用户信息
        $api->patch('user', 'UsersController@update')
            ->name('api.user.update');
        // 发送激活邮件
        $api->post('user/mails', 'UserEmailController@send')
            ->name('api.user.mails.send');

        /**
         * 用户认证相关
         */
        $api->group([
            'middleware' => 'api.throttle',
            'limit'      => 5,
            'expires'    => 1
        ], function ($api) {
            // 修改密码
            $api->patch('user/password', 'UserPasswordController@changePassword')
                ->name('api.user.password.update');
            // 修改手机号
            $api->patch('user/phone', 'UserPasswordController@changePhone')
                ->name('api.user.phone.update');
        });

        /**
         * 关注相关
         */
        // 关注一名用户
        $api->put('user/following/{user}', 'UsersController@follow')
            ->name('api.user.follow');
        // 取消关注
        $api->delete('user/following/{user}', 'UsersController@unfollow')
            ->name('api.user.unfollow');
        // 推荐关注的设计师
        $api->get('user/recommend', 'UsersController@recommend')
            ->name('api.user.recommendDesigner');

        /**
         * 文件上传
         */
        $api->post('uploads', 'UploadsController@store')
            ->name('api.uploads.store');

        /**
         * 动态相关
         */
        // 首页：关注的人动态
        $api->get('user/feeds', 'ActivitiesController@feeds')
            ->name('api.user.feeds');
        // 发布动态
        $api->post('activities', 'ActivitiesController@store')
            ->name('api.activities.store');
        // 删除动态
        $api->delete('activities/{activity}', 'ActivitiesController@destroy')
            ->where('activity', '[0-9]+')
            ->name('api.activities.delete');
        // 点赞动态
        $api->post('activities/{activity}/likes', 'ActivityLikesController@store')
            ->name('api.activities.likes.store');
        // 取消点赞
        $api->delete('activities/{activity}/likes', 'ActivityLikesController@destroy')
            ->name('api.activities.likes.destroy');

        /**
         * 动态评论相关
         */
        // 评论一条动态
        $api->post('activities/{activity}/replies', 'RepliesController@store')
            ->name('api.activities.replies.store');
        // 删除一条评论
        $api->delete('activities/{activity}/replies/{reply}', 'RepliesController@destroy')
            ->name('api.activities.replies.destroy');
        // 当前用户发表的评论
        $api->get('user/replies', 'RepliesController@userIndex')
            ->name('api.user.replies.index');

        /**
         * 评价相关
         */
        // 邀请一名用户评价
        $api->post('user/reviews/users', 'UserReviewsController@invite');
        // 评价一名用户
        $api->post('users/{user}/reviews', 'UserReviewsController@store')
            ->where('user', '[0-9]+')
            ->name('api.users.reviews.store');
        // 删除一条评价
        $api->delete('reviews/{review}', 'UserReviewsController@destroy')
            ->where('review', '[0-9]+')
            ->name('api.reviews.destroy');
        // 获取评价状态：是否评价了某个用户
        $api->get('user/reviews/status', 'UserReviewsController@status')
            ->name('api.user.reviews.status');

        /**
         * 项目相关
         */
        // 获取项目详情
        $api->get('projects/{project}', 'ProjectsController@index')
            ->name('api.projects.index');
        // 发布项目
        $api->post('projects', 'ProjectsController@store')
            ->name('api.projects.store');
        // 删除项目
        $api->delete('projects/{project}', 'ProjectsController@destroy')
            ->name('api.projects.destroy');
        // 补充项目
        $api->patch('projects/{project}', 'ProjectsController@update')
            ->name('api.projects.update');
        // 申请重新审核项目
        $api->put('user/reviewing/projects/{project}', 'ProjectsController@reReview')
            ->name('api.projects.reReview');
        // 取消项目
        $api->put('user/canceled/projects/{project}', 'ProjectsController@cancel')
            ->name('api.user.projects.cancel');
        // 收藏项目
        $api->put('user/favoriting/projects/{project}', 'ProjectFavoritesController@store')
            ->name('api.user.projects.favorite');
        // 取消收藏
        $api->delete('user/favoriting/projects/{project}', 'ProjectFavoritesController@destroy')
            ->name('api.user.projects.unfavorite');
        // 报名项目
        $api->post('projects/{project}/applications', 'ProjectApplicationsController@store')
            ->name('api.projects.apply');
        // 取消报名
        $api->delete('user/applying/projects/{project}', 'ProjectApplicationsController@destroy')
            ->name('api.user.projects.cancelApply');
        // 获取项目报名列表
        $api->get('projects/{project}/applications', 'ProjectApplicationsController@applications')
            ->name('api.projects.applications.index');
        // 获取报名项目详情
        $api->get('projects/{project}/applications/{projectApplication}', 'ProjectApplicationsController@index')
            ->name('api.applications.index');
        // 当前登录用户发布或报名的项目
        $api->get('user/projects', 'ProjectsController@userIndex')
            ->name('api.user.projects.index');
        // 当前登录用户进行中的项目
        $api->get('user/processing/projects', 'ProjectsController@processing')
            ->name('api.user.projects.processing');
        // 当前登录用户收藏的项目
        $api->get('user/favoriting/projects', 'ProjectsController@favorite')
            ->name('api.user.projects.favorite');

        /**
         * 通知相关
         */
        // 通知列表
        $api->get('user/notifications', 'NotificationsController@index')
            ->name('api.user.notifications.index');
        // 通知统计
        $api->get('user/notifications/stats', 'NotificationsController@stats')
            ->name('api.user.notifications.stats');
        // 标记某个用户所有通知为已读
        $api->patch('user/read/notifications', 'NotificationsController@readAll')
            ->name('api.user.notifications.readAll');
        // 标记单个通知为已读
        $api->put('user/read/notifications/{notification}', 'NotificationsController@readOne')
            ->name('api.user.notifications.readOne');
        // 删除某条通知
        $api->delete('notifications/{notification}', 'NotificationsController@destroy')
            ->name('api.notifications.destroy');
        // 删除所有已读通知
        $api->delete('user/read/notifications', 'NotificationsController@destroyAllRead')
            ->name('api.user.notifications.destroyRead');

        /**
         * 作品集相关
         */
        // 创建作品
        $api->post('works', 'WorksController@store')
            ->name('api.works.store');
        // 修改作品内容
        $api->patch('works/{work}', 'WorksController@update')
            ->name('api.works.update');
        // 删除作品
        $api->delete('works/{work}', 'WorksController@destroy')
            ->name('api.works.destroy');

        /**
         * 私信相关
         */
        // 获取当前用户的私信列表
        $api->get('user/messages', 'MessagesController@index')
            ->name('api.user.messages.index');
        // 获取某个私信的消息列表
        $api->get('messages/{thread}', 'MessagesController@threadIndex')
            ->name('api.messages.threadIndex');
        // 发送一条私信
        $api->post('messages/{thread}', 'MessagesController@storeByThreadId')
            ->name('api.messages.store');
        // 给某个用户发送一条私信
        $api->post('user/messages', 'MessagesController@store')
            ->name('api.user.messages.store');
    });
});