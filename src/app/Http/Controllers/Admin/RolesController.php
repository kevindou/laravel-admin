<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Admin;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $model = 'App\Models\Admin\Role';

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::roles.index');
    }

    /**
     * 处理查询参数配置
     *
     * @return array
     */
    public function where()
    {
        return [
            'name'         => 'like',
            'display_name' => 'like',
        ];
    }

    /**
     * 创建数据
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        /* @var $model \Illuminate\Database\Eloquent\Model */
        $request             = request();
        $model               = new $this->model;
        $model->name         = $request->input('name');
        $model->display_name = $request->input('display_name');
        $model->description  = $request->input('description');
        if (!$model->save()) {
            return $this->error(1005);
        }

        // 添加角色
        if ($model->getTable() === 'roles') {
            if ($user = admin::where(['id' => 1])->first()) {
                $user->roles()->attach($model->id);
            }
        } else {
            if ($role = Role::where(['name' => 'admin'])->first()) {
                $role->perms()->attach($model->id);
            }
        }

        return $this->success($model);
    }

    /**
     * 修改事件信息
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update()
    {
        $request             = request();
        $model               = $this->findOrFail();
        $model->name         = $request->input('name');
        $model->display_name = $request->input('display_name');
        $model->description  = $request->input('description');
        if ($model->save()) {
            return $this->success($model);
        }

        return $this->error(1007);
    }

    /**
     * 删除数据
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete()
    {
        if ((new $this->model)->where('id', request()->input('id'))->delete()) {
            return $this->success([]);
        }

        return $this->error(1006);
    }

    /**
     * 分配权限信息
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function permissions(Request $request)
    {
        $id = (int)$request->get('id');
        if ($id == 1) {
            $request->session()->flash('error', trans('admin.notAllowedSetAdmin'));
            return redirect('/admin/roles/index');
        }

        // 查询角色
        $model = Role::findOrFail($id);
        if ($request->isMethod('post')) {
            $model->name         = $request->input('name');
            $model->display_name = $request->input('display_name');
            $model->description  = $request->input('description');
            if ($model->save()) {
                $model->perms()->sync($request->input('permissions'));
                return redirect('/admin/roles/index');
            }
        }

        view()->share([
            'title'           => trans('分配权限'),
            '__active_menu__' => 'admin/roles/index'
        ]);
        
        // 查询全部权限
        $permissions = Permission::all();
        return view('admin::roles.permissions', [
            'model'       => $model,
            'permissions' => $permissions,
        ]);
    }
}
