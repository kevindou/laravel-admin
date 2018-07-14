<?php

namespace App\Models\Admin;

use App\Models\Model;

class RoleMenus extends Model
{
    protected $table      = 'role_menus';
    public    $timestamps = false;
    public    $columns    = [
        'id',
        'role_id',
        'menu_id',
    ];
}