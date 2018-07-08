<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Calendar;
use App\Repositories\Repository;

class CalendarRepository extends Repository
{
    public function __construct(Calendar $model)
    {
        $this->model = $model;
    }
}