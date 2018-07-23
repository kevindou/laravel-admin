<?php

use \Illuminate\Support\Facades\Route;

$uri    = trim(request()->getPathInfo(), '/');
$params = $uri == '' ? [] : explode('/', $uri);
// 将 get-user-info 转为 GetUserInfo
array_studly_case($params);
// 获取到 控制器 和 action
list($controller, $action) = get_controller_action($params);

$namespace  = '\App\Http\Controllers\\';
$options    = ['domain' => request()->getHttpHost(), 'namespace' => $namespace];
$controller = $namespace . $controller;
if (method_exists($controller, $action)) {
    Route::group($options, function () use ($uri, $controller, $action) {
        Route::match(['get', 'post'], $uri, $controller . '@' . $action);
    });
}






