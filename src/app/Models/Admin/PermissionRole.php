<?php

namespace App\Models\Admin;

use App\Models\Model;

class PermissionRole extends Model
{
    public $table = 'permission_role';

    public $timestamps = false;

    public $columns = [
        'permission_id',
        'role_id'
    ];
}
