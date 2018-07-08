<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Permission;
use App\Repositories\Repository;

class PermissionRepository extends Repository
{
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }
}