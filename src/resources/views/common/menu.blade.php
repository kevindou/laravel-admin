<?php
if (config('admin.home')) {
    array_unshift($menus, [
        'id'   => 0,
        'icon' => 'fa-home',
        'url'  => '/admin/index',
        'name' => trans('admin.home'),
    ]);
}

$nav_html = render_menu($menus, [
    'ul' => [
        'class'    => 'sidebar-menu',
        'id'       => 'admin-menus',
        'data-url' => '/' . request()->path()
    ]
])
?>
{!! $nav_html !!}