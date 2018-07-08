<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Upload;
use App\Repositories\Repository;

class UploadRepository extends Repository
{
    public function __construct(Upload $model)
    {
        $this->model = $model;
    }
}