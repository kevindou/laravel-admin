<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* @var \Illuminate\Support\Facades\Route */

use \Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

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
            $action     = 'action' . ucfirst($params ? array_pop($params) : 'index');
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

//Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
//    Route::view('/', 'admin.index.index')
//        ->name('admin.index')->middleware('admin');
//    Route::view('/index', 'admin.index.index')
//        ->name('admin.index')->middleware('admin');
//
//    // 用户登录和退出
//    Route::get('/login', 'LoginController@showLoginForm')->name('admin.login.form');
//    Route::post('/login', 'LoginController@login')->name('admin.login');
//    Route::post('/logout', 'LoginController@logout')->name('admin.logout');
//
//    // 管理员信息
//    Route::get('/admins/index', 'AdminsController@index')->name('admin.admins.index');
//    Route::get('/admins/search', 'AdminsController@search')->name('admin.admins.search');
//    Route::post('/admins/update', 'AdminsController@update')->name('admin.admins.update');
//    Route::post('/admins/create', 'AdminsController@create')->name('admin.admins.create');
//    Route::post('/admins/delete', 'AdminsController@delete')->name('admin.admins.delete');
//
//    // 导航栏目
//    Route::get('/menus/index', 'MenusController@index')->name('admin.menus.index');
//    Route::get('/menus/search', 'MenusController@search')->name('admin.menus.search');
//    Route::post('/menus/update', 'MenusController@update')->name('admin.menus.update');
//    Route::post('/menus/create', 'MenusController@create')->name('admin.menus.create');
//    Route::post('/menus/delete', 'MenusController@delete')->name('admin.menus.delete');
//
//    // 角色管理
//    Route::get('/roles/index', 'RolesController@index')->name('admin.roles.index');
//    Route::get('/roles/search', 'RolesController@search')->name('admin.roles.search');
//    Route::post('/roles/update', 'RolesController@update')->name('admin.roles.update');
//    Route::post('/roles/create', 'RolesController@create')->name('admin.roles.create');
//    Route::post('/roles/delete', 'RolesController@delete')->name('admin.roles.delete');
//    Route::get('/roles/permissions/{id}', 'RolesController@permissions')->name('admin.roles.permissions');
//    Route::post('/roles/permissions/{id}', 'RolesController@permissions');
//
//    // 权限管理
//    Route::get('/permissions/index', 'PermissionsController@index')->name('admin.permissions.index');
//    Route::get('/permissions/search', 'PermissionsController@search')->name('admin.permissions.search');
//    Route::post('/permissions/update', 'PermissionsController@update')->name('admin.permissions.update');
//    Route::post('/permissions/create', 'PermissionsController@create')->name('admin.permissions.create');
//    Route::post('/permissions/delete', 'PermissionsController@delete')->name('admin.permissions.delete');
//
//    // 日程管理
//    Route::get('/calendars/index', 'CalendarsController@index')->name('admin.calendars.index');
//    Route::get('/calendars/search', 'CalendarsController@search')->name('admin.calendars.search');
//    Route::get('/calendars/self', 'CalendarsController@self')->name('admin.calendars.self');
//    Route::post('/calendars/update', 'CalendarsController@update')->name('admin.calendars.update');
//    Route::post('/calendars/create', 'CalendarsController@create')->name('admin.calendars.create');
//    Route::post('/calendars/delete', 'CalendarsController@delete')->name('admin.calendars.delete');
//    Route::get('/calendars/events', 'CalendarsController@events')->name('admin.calendars.events');
//
//    // 文件上传功能
//    Route::get('/uploads/index', 'UploadsController@index')->name('admin.uploads.index');
//    Route::get('/uploads/list', 'UploadsController@list')->name('admin.uploads.list');
//    Route::post('/uploads/upload', 'UploadsController@upload')->name('admin.uploads.upload');
//    Route::post('/uploads/delete', 'UploadsController@delete')->name('admin.uploads.delete');
//    Route::post('/uploads/update', 'UploadsController@update')->name('admin.uploads.update');
//    Route::get('/uploads/download', 'UploadsController@download')->name('admin.uploads.download');
//});

Auth::routes();
