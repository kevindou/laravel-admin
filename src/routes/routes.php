<?php

/* @var \Illuminate\Support\Facades\Route */

use \Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    $uri    = request()->path();
    $params = isset($uri[0]) ? explode('/', $uri) : [];
    array_shift($params);   // 因为是admin 前缀分组信息，那么下面的路由需要去掉 admin 前缀
    $uri = implode('/', $params);
    foreach ($params as &$val) {
        $val = camel_case($val);;
    }

    unset($val);

    switch (count($params)) {
        case 0:
            // 根路由走默认控制器
            $controller = config('admin.defaultController');
            $action     = config('admin.defaultAction');
            break;
        case 1:
            // 一个参数认为是控制器
            $controller = $params[0] . 'Controller';
            $action     = config('admin.defaultAction');
            break;
        default:
            // 2个以上，倒数第一个为action，倒数第二个为控制器，其他为命名空间
            $action     = strtolower(array_pop($params));
            $controller = implode('\\', array_map('ucfirst', $params)) . 'Controller';
            break;
    }

    $namespace  = '\App\Http\Controllers\Admin\\';
    $options    = ['domain' => request()->getHttpHost(), 'namespace' => $namespace];
    $controller = $namespace . $controller;
    if (method_exists($controller, $action)) {
        Route::group($options, function () use ($uri, $controller, $action) {
            Route::match(['get', 'post'], $uri, $controller . '@' . $action);
        });
    }
});





