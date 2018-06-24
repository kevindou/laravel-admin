Laravel Admin 后台模块
======================

后台使用 AdminLTE 模板，带有权限管理和菜单管理

## Installation

安装此扩展程序的首选方式是通过[composer](http://getcomposer.org/download/)。

命令行运行
```
composer require jinxing/laravel-admin "~1.0.0"
```
目前还没有将包发布到packages上面,需要在 **composer.json** 中添加配置
```json
{
    "repositories": [
        {
            "type": "git",
            "url": "git@github.com:myloveGy/laravel-admin.git"
        }
    ]
}
```

### 注册服务提供者
```php
php artisan vendor:publish --provider="App\Provider\AdminProvider"
```

### 添加后台路由(routes/web.php)
```php
// 在routes/web.php 中添加
require admin_path('routes/rotes.php');
```
### 添加配置别名(app/Http/Kernel.php)
```php
// 在$routeMiddleware数组中追加
protected $routeMiddleware = [
    // ... 
    'admin'      => \App\Http\Middleware\AdminAuth::class,
    'role'       => \Zizaco\Entrust\Middleware\EntrustRole::class,
    'permission' => \Zizaco\Entrust\Middleware\EntrustPermission::class,
    'ability'    => \Zizaco\Entrust\Middleware\EntrustAbility::class,
];
```

### 数据库迁移
```
php artisan migrate 
```

### 添加数据
```
php artisan db:seed --class=AdminsSeeder
```