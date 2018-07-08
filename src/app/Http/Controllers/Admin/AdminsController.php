<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Admin;
use App\Repositories\Admin\AdminRepository;
use App\Http\Requests\Admin\Admins\DeleteRequest;
use App\Http\Requests\Admin\Admins\StoreRequest;
use App\Http\Requests\Admin\Admins\UpdateRequest;

/**
 * Class AdminsController 后台管理员信息
 *
 * @package App\Http\Controllers\Admin
 */
class AdminsController extends Controller
{
    public function __construct(AdminRepository $adminRepository)
    {
        parent::__construct();
        $this->repository = $adminRepository;
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::admins.index', [
            'status' => admin::getStatus()
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
