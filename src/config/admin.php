<?php

return [
    // 配置信息
    'defaultController'    => 'IndexController',
    'defaultAction'        => 'index',
    'super_admin_id'       => 1,    // 超级管理员ID
    'super_role_id'        => 1,    // 超级管理员角色ID

    // 页面信息
    'admin_left'           => false,           // 左边用户信息是否显示
    'home'                 => false,           // 首页链接是否显示
    'messages-menu'        => false,           // 邮件
    'notifications-menu'   => false,           // 通知
    'tasks-menu'           => false,           // task
    'comments'             => false,

    // 用户相关
    'user_schedule_events' => true,           // 我的日程是否显示
    'user_detail'          => true,           // 用户详情
    'verify_permissions'   => true,           // 验证权限

    'login_url'  => '/admin/index/index', // 登录成功跳转地址
    'logout_url' => 'admin/login/index', // 退出后跳转地址
];