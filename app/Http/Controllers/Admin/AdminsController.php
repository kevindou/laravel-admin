<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;

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
     * @param array $params
     * @return array
     */
    public function where($params)
    {
        return [
            'name' => 'like',
            'email' => '='
        ];
    }

    /**
     * 创建数据
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        /* @var $model \App\Models\Admin */
        $model = new $this->model;
        $array = request()->input();
        if (!empty($array['password'])) {
            $array['password'] = bcrypt($array['password']);
        }

        $model->fill($array);
        if ($model->save()) {
            return $this->success($model);
        } else {
            return $this->error(1005);
        }
    }

    /**
     * 修改事件信息
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update()
    {
        $model = $this->findOrFail();
        $array = request()->input();
        if (!empty($array['password'])) {
            $array['password'] = bcrypt($array['password']);
        } else {
            unset($array['password']);
        }

        $model->fill($array);
        if ($model->save()) {
            return $this->success($model);
        } else {
            return $this->error(1007);
        }
    }
}
