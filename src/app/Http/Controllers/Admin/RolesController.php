<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Roles\DestroyRequest;
use App\Http\Requests\Admin\Roles\StoreRequest;
use App\Http\Requests\Admin\Roles\UpdateRequest;
use App\Models\Admin\Admin;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use App\Repositories\Admin\RoleRepository;
use App\Repositories\Admin\RoleUserRepository;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * @var RoleUserRepository
     */
    private $roleUserRepository;

    public function __construct(RoleRepository $repository, RoleUserRepository $roleUserRepository)
    {
        parent::__construct();
        $this->repository         = $repository;
        $this->roleUserRepository = $roleUserRepository;
    }

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
            'description'  => 'like',
            'display_name' => 'like',
        ];
    }

    /**
     * 添加数据
     *
     * @param StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        return $this->sendJson($this->repository->create($request->all()));
    }

    /**
     * 修改数据
     *
     * @param UpdateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        return $this->sendJson($this->repository->update($request->input('id'), $request->all()));
    }

    /**
     * 删除数据
     *
     * @param DestroyRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        // 查询角色是否在使用
        $id = $request->input('id');
        if ($this->roleUserRepository->findOne(['role_id' => $id])) {
            return $this->error(1008);
        }

        return $this->sendJson($this->repository->deleteRole(['id' => $id]));
    }

    /**
     * 分配权限信息
     *
     * @param Request $request
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
