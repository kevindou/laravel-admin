<?php

namespace App\Models\Admin;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
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
