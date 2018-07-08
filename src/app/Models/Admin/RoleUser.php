<?php

namespace App\Models\Admin;

use App\Models\Model;

class RoleUser extends Model
{
    public $table = 'role_user';

    public $timestamps = false;

    public $columns = [
        'user_id',
        'role_id'
    ];
}