<?php

namespace App\Repositories\Admin;

use App\Models\Admin\RoleUser;
use App\Repositories\Repository;

class RoleUserRepository extends Repository
{
    public function __construct(RoleUser $model)
    {
        $this->model = $model;
    }
}