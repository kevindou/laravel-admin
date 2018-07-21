<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RoleUsers\DestroyRequest;
use App\Http\Requests\Admin\RoleUsers\StoreRequest;
use App\Repositories\Admin\AdminRepository;
use App\Repositories\Admin\RoleRepository;
use App\Repositories\Admin\RoleUserRepository;

class RoleUsersController extends Controller
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;
    /**
     * @var AdminRepository
     */
    private $adminRepository;

    public function __construct(
        RoleUserRepository $menuRepository,
        RoleRepository $roleRepository,
        AdminRepository $adminRepository
    )
    {
        parent::__construct();
        $this->repository      = $menuRepository;
        $this->roleRepository  = $roleRepository;
        $this->adminRepository = $adminRepository;
    }

    public function where()
    {
        return [
            'user_id' => '=',
            'role_id' => '='
        ];
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $admins = $this->adminRepository->findAllToIndex([], ['id', 'name'], 'id', 'name');
        $roles  = $this->roleRepository->findAllToIndex([], ['id', 'display_name'], 'id', 'display_name');
        // 载入视图
        return view('admin::role_users.index', compact('admins', 'roles'));
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
     * 删除数据
     *
     * @param DestroyRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        return $this->sendJson($this->repository->delete($request->all()));
    }
}
