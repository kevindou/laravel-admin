<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Menu;
use App\Repositories\Repository;

class MenuRepository extends Repository
{
    public function __construct(Menu $model)
    {
        $this->model = $model;
    }
}