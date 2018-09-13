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
    $router->resource('remittances', RemittanceController::class, [
        'only' => ['index', 'show', 'edit']
    ]);
    $router->post('/projects/{project}/remittances', 'RemittanceController@update');
});

