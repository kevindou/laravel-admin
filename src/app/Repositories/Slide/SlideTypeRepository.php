<?php

namespace App\Repositories\Slide;

use App\Models\Slide\SlideType;
use App\Repositories\Repository;

class SlideTypeRepository extends Repository
{
    public function __construct(SlideType $model)
    {
        $this->model = $model;
    }
}