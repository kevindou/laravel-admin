<?php
/**
 *
 * ResponseTrait.php
 *
 * Author: jinxing.liu@verystar.cn
 * Create: 2018/6/13 14:53
 * Editor: created by PhpStorm
 */

namespace App\Traits;


trait ResponseTrait
{
    protected function error($msg = 'fail', $data = null)
    {
        return [false, $msg, $data];
    }

    protected function success($data = [], $msg = 'success')
    {
        return [true, $msg, $data];
    }
}