<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Menus\DestroyRequest;
use App\Http\Requests\Admin\Menus\StoreRequest;
use App\Http\Requests\Admin\Menus\UpdateRequest;
use App\Models\Admin\Menu;
use App\Repositories\Admin\MenuRepository;

class MenusController extends Controller
{
    public function __construct(MenuRepository $menuRepository)
    {
        parent::__construct();
        $this->repository = $menuRepository;
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // 默认状态信息
        $status = Menu::getStatus();

        // 查询父类等级
        $parents = $this->repository->findAllToIndex([
            'status:neq' => Menu::STATUS_DELETE,
            'parent'     => 0
        ], '*', 'id', 'name');

        // 载入视图
        return view('admin::menus.index', compact('status', 'parents'));
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
            'name:like' => array_get($condition, 'name'),
            'url:like'  => array_get($condition, 'url'),
            'status:in' => array_get($condition, 'status')
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
     */
    public function destroy(DestroyRequest $request)
    {
        return $this->sendJson($this->repository->delete($request->input('id')));
    }
}
