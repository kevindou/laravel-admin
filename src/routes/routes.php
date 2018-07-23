<?php

use \Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    $uri    = request()->path();
    $params = isset($uri[0]) ? explode('/', $uri) : [];
    // 因为是admin 前缀分组信息，那么下面的路由需要去掉 admin 前缀
    array_shift($params);
    $uri = implode('/', $params);
    // 将 get-user-info 转为 GetUserInfo
    array_studly_case($params);
    // 获取到 控制器 和 action
    list($controller, $action) = get_controller_action($params);

    $namespace  = '\App\Http\Controllers\Admin\\';
    $options    = ['domain' => request()->getHttpHost(), 'namespace' => $namespace];
    $controller = $namespace . $controller;
    if (method_exists($controller, $action)) {
        Route::group($options, function () use ($uri, $controller, $action) {
            Route::match(['get', 'post'], $uri, $controller . '@' . $action);
        });
    }
});





