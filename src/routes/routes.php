<?php

/* @var \Illuminate\Support\Facades\Route */

use \Illuminate\Support\Facades\Route;

if (request()->is('admin*')) {
    $uri    = trim(request()->getPathInfo(), '/');
    $params = isset($uri[0]) ? explode('/', $uri) : [];
    foreach ($params as $i => $v) {
        $vv         = explode('-', $v);
        $params[$i] = implode('', array_map(function ($v) {
                return ucfirst($v);
            }, $vv)
        );
    }

// 去掉admin 控制器
    array_shift($params);

    $defaultController = 'IndexController';
    $defaultAction     = 'actionIndex';
    switch (count($params)) {
        case 0:
            $controller = $defaultController;
            $action     = $defaultAction;
            break;
        case 1:
            $controller = $params[0] . 'Controller';
            $action     = $defaultAction;
            break;
        default:
            $action     = strtolower($params ? array_pop($params) : 'index');
            $controller = ($params ? implode('\\', array_map('ucfirst', $params)) : 'Index') . 'Controller';
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
}



