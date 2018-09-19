<?php

namespace App\Repositories\Nav;

use App\Models\Nav\NavType;
use App\Repositories\Repository;

class NavTypeRepository extends Repository
{
    public function __construct(NavType $model)
    {
        $this->model = $model;
    }
}