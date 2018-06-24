<?php

/* @var \Illuminate\Support\Facades\Route */

use \Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    $uri    = trim(request()->getPathInfo(), '/');
    $params = isset($uri[0]) ? explode('/', $uri) : [];
    array_shift($params);
    $uri = implode('/', $params);
    foreach ($params as $i => $v) {
        $vv         = explode('-', $v);
        $params[$i] = implode('', array_map(function ($v) {
                return ucfirst($v);
            }, $vv)
        );
    }

    $defaultController = 'IndexController';
    $defaultAction     = 'index';
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
});





