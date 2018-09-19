<?php

namespace App\Repositories\Nav;

use App\Models\Nav\Nav;
use App\Repositories\Repository;

class NavRepository extends Repository
{
    public function __construct(Nav $model)
    {
        $this->model = $model;
    }
}