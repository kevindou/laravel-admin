<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Tree;
use App\Http\Requests\Admin\Roles\DestroyRequest;
use App\Http\Requests\Admin\Roles\StoreRequest;
use App\Http\Requests\Admin\Roles\UpdateRequest;
use App\Repositories\Admin\MenuRepository;
use App\Repositories\Admin\PermissionRepository;
use App\Repositories\Admin\PermissionRoleRepository;
use App\Repositories\Admin\RoleMenusRepository;
use App\Repositories\Admin\RoleRepository;
use App\Repositories\Admin\RoleUserRepository;
use App\Traits\UserTrait;

class RolesController extends Controller
{
    use UserTrait;

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

    /**
     * @var MenuRepository
     */
    private $menuRepository;

    /**
     * @var RoleMenusRepository
     */
    private $roleMenusRepository;

    public function __construct(
        RoleRepository $repository,
        RoleUserRepository $roleUserRepository,
        PermissionRepository $permissionRepository,
        PermissionRoleRepository $permissionRoleRepository,
        MenuRepository $menuRepository,
        RoleMenusRepository $roleMenusRepository
    )
    {
        parent::__construct();
        $this->repository               = $repository;
        $this->roleUserRepository       = $roleUserRepository;
        $this->permissionRepository     = $permissionRepository;
        $this->permissionRoleRepository = $permissionRoleRepository;
        $this->menuRepository           = $menuRepository;
        $this->roleMenusRepository      = $roleMenusRepository;
    }

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
     * 获取查询的 model
     *
     * @param array|mixed $condition 查询条件
     *
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function findModel($condition)
    {
        return $this->repository->getFilterModel([
            'name:like'         => array_get($condition, 'name'),
            'description:like'  => array_get($condition, 'description'),
            'display_name:like' => array_get($condition, 'display_name'),
        ]);
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
        $menus       = $this->menuRepository->findAll();
        $menuIds     = $this->roleMenusRepository->findAllColumn(['role_id' => $id], 'menu_id');
        $hasIds      = $this->permissionRoleRepository->findAllColumn(['role_id' => $id], 'permission_id');

        $tree  = (new Tree([
            'parentIdName' => 'parent',
            'childrenName' => 'children',
            'array'        => $menus,
        ]))->getTreeArray(0);
        $trees = $this->menuRepository->getJsMenus($tree, $menuIds);
        return view(
            'admin::roles.permissions',
            compact('role', 'permissions', 'hasIds', 'trees')
        );
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
        $data    = $request->all();
        $role_id = intval(array_get($data, 'id'));
        $this->roleMenusRepository->updateMenus($role_id, array_get($data, 'menu_ids'));
        $this->repository->updatePermissions($role_id, $data, array_get($data, 'permissions', []));
        return redirect('/admin/roles/index');
    }
}
