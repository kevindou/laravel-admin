<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    public $columns = [
        'id',
        'name',
        'display_name',
        'description',
        'created_at',
        'updated_at',
    ];
}
