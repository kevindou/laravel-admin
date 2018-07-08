<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Role;
use App\Repositories\Repository;

class RoleRepository extends Repository
{
    public function __construct(Role $model)
    {
        $this->model = $model;
    }
}