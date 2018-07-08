<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Roles\DestroyRequest;
use App\Http\Requests\Admin\Roles\StoreRequest;
use App\Http\Requests\Admin\Roles\UpdateRequest;
use App\Repositories\Admin\PermissionRepository;
use App\Repositories\Admin\PermissionRoleRepository;
use App\Repositories\Admin\RoleRepository;
use App\Repositories\Admin\RoleUserRepository;

class RolesController extends Controller
{
    /**
     * @var RoleUserRepository
     */
    private $roleUserRepository;

    /**
     * @var PermissionRepository
     */
    private $permissionRepository;
    /**
     * @var PermissionRoleRepository
     */
    private $permissionRoleRepository;

    public function __construct(
        RoleRepository $repository,
        RoleUserRepository $roleUserRepository,
        PermissionRepository $permissionRepository,
        PermissionRoleRepository $permissionRoleRepository
    )
    {
        parent::__construct();
        $this->repository               = $repository;
        $this->roleUserRepository       = $roleUserRepository;
        $this->permissionRepository     = $permissionRepository;
        $this->permissionRoleRepository = $permissionRoleRepository;
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
     * @throws \Throwable
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
     * 分配权限
     *
     * @param DestroyRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function permissions(DestroyRequest $request)
    {
        $id = (int)$request->get('id');
        if ($id == 1) {
            $request->session()->flash('error', trans('admin.notAllowedSetAdmin'));
            return redirect('/admin/roles/index');
        }

        view()->share([
            'title'           => trans('分配权限'),
            '__active_menu__' => 'admin/roles/index'
        ]);

        $role        = $this->repository->findOne($id);
        $permissions = $this->permissionRepository->findAll();
        $hasIds      = $this->permissionRoleRepository->findAllColumn(['role_id' => $id], 'permission_id');
        return view('admin::roles.permissions', compact('role', 'permissions', 'hasIds'));
    }

    /**
     * 修改权限
     *
     * @param UpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function updatePermissions(UpdateRequest $request)
    {
        $data = $request->all();
        $this->repository->updatePermissions(array_get($data, 'id'), $data, array_get($data,'permissions', []));
        return redirect('/admin/roles/index');
    }
}
