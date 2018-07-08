<?php

namespace App\Models\Admin;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    public $fillable = [
        'name',
        'display_name',
        'description',
        'created_at',
        'updated_at',
    ];

    public $columns = [
        'id',
        'name',
        'display_name',
        'description',
        'created_at',
        'updated_at',
    ];
}
