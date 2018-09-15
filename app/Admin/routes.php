<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('users', UserController::class);
    $router->resource('projects', ProjectController::class, [
        'except' => ['create', 'store']
    ]);
    $router->resource('activities', ActivityController::class, [
        'only' => ['index', 'show', 'destroy']
    ]);
    $router->resource('replies', ReplyController::class, [
        'only' => ['index',  'destroy']
    ]);
    $router->resource('replies', ReplyController::class, [
        'only' => ['index',  'destroy']
    ]);
    $router->resource('project_remittances', RemittanceController::class, [
        'only' => ['index', 'show', 'edit']
    ]);
    $router->post('/projects/{project}/remittances', 'RemittanceController@update');

    // 设计费发放
    $router->resource('project_payments', PaymentController::class, [
        'only' => ['index', 'show']
    ]);
    // 设计费发放表单
    $router->get('/payments/form', 'PaymentController@form');
    // 创建设计费发放条目
    $router->post('/payments/update', 'PaymentController@update');
    // 删除设计费发放条目
    $router->delete('/payments/{payment}', 'PaymentController@destroy');
});

