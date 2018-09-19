<?php

namespace App\Repositories\Slide;

use App\Models\Slide\Slide;
use App\Repositories\Repository;

class SlideRepository extends Repository
{
    public function __construct(Slide $model)
    {
        $this->model = $model;
    }
}