<?php
/**
 *
 * AdminRepository.php
 *
 * Author: jinxing.liu@verystar.cn
 * Create: 2018/6/13 16:13
 * Editor: created by PhpStorm
 */

namespace App\Repositories;

use App\Models\Admin;

class AdminRepository extends Repository
{
    public function __construct(Admin $model)
    {
        $this->model = $model;
    }
}