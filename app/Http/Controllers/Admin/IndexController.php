<?php
/**
 *
 * IndexController.php
 *
 * Author: jinxing.liu@verystar.cn
 * Create: 2018/6/14 17:16
 * Editor: created by PhpStorm
 */

namespace App\Http\Controllers\Admin;

class IndexController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function actionIndex()
    {
        return view('admin.index.index');
    }
}