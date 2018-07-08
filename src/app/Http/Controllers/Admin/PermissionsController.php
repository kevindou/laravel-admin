<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Role;
use App\Http\Requests\Admin\Permissions\DestroyRequest;
use App\Http\Requests\Admin\Permissions\StoreRequest;
use App\Http\Requests\Admin\Permissions\UpdateRequest;
use App\Repositories\Admin\PermissionRepository;

/**
 * Class PermissionsController 权限信息操作控制器
 *
 * @package App\Http\Controllers\Admin
 */
class PermissionsController extends Controller
{
    public function __construct(PermissionRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::permissions.index');
    }

    /**
     * 添加数据
     *
     * @param StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreRequest $request)
    {
        return $this->sendJson($this->repository->createPermission($request->all()));
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
        return $this->sendJson($this->repository->deletePermission(['id' => $request->input('id')]));
    }
}
