<?php

namespace App\Repositories\Admin;

use App\Models\Admin\PermissionRole;
use App\Repositories\Repository;

class PermissionRoleRepository extends Repository
{
    public function __construct(PermissionRole $model)
    {
        $this->model = $model;
    }
}