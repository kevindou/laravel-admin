<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Admin;
use App\Repositories\Admin\AdminRepository;
use App\Http\Requests\Admin\Admins\DeleteRequest;
use App\Http\Requests\Admin\Admins\StoreRequest;
use App\Http\Requests\Admin\Admins\UpdateRequest;
use App\Repositories\Admin\RoleRepository;
use App\Repositories\Admin\RoleUserRepository;

/**
 * Class AdminsController 后台管理员信息
 *
 * @package App\Http\Controllers\Admin
 */
class AdminsController extends Controller
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;
    /**
     * @var RoleUserRepository
     */
    private $roleUserRepository;

    public function __construct(
        AdminRepository $adminRepository,
        RoleRepository $roleRepository,
        RoleUserRepository $roleUserRepository
    )
    {
        parent::__construct();
        $this->repository         = $adminRepository;
        $this->roleRepository     = $roleRepository;
        $this->roleUserRepository = $roleUserRepository;
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $roles = $this->roleRepository->findAllToIndex([], '*', 'id', 'name');
        return view('admin::admins.index', [
            'status' => admin::getStatus(),
            'roles'  => $roles
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
     * 添加数据
     *
     * @param StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $request->all();
        list($ok, $msg, $admin) = $this->repository->create($data);
        if (!$ok) {
            return $this->error(1000, $msg);
        }

        // 添加角色
        $this->roleUserRepository->createUserRoles(array_get($admin, 'id'), array_get($data, 'role_ids'));
        return $this->success($admin);
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
        $id   = $request->input('id');
        $data = $request->all();
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        }

        return $this->sendJson($this->repository->update($id, $data));
    }

    /**
     * 删除数据
     *
     * @param DeleteRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteRequest $request)
    {
        return $this->sendJson($this->repository->delete($request->input('id')));
    }
}
