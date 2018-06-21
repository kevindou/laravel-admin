<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;

/**
 * Class AdminsController 后台管理员信息
 *
 * @package App\Http\Controllers\Admin
 */
class AdminsController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $model = 'App\Models\Admin';

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.admins.index', [
            'status' => Admin::getStatus()
        ]);
    }

    /**
     * 处理显示查询参数配置
     *
     * @return array
     */
    public function where()
    {
        return [
            'name'  => 'like',
            'email' => '='
        ];
    }

    /**
     * 处理请求中的密码字段
     *
     * @return array|string
     */
    protected function handleRequest()
    {
        if ($array = request()->input()) {
            if (!array_get($array, 'password')) {
                unset($array['password']);
            }
        }

        return $array;
    }
}
