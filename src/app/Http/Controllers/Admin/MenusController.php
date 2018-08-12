<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Tree;
use App\Http\Requests\Admin\Menus\DestroyRequest;
use App\Http\Requests\Admin\Menus\StoreRequest;
use App\Http\Requests\Admin\Menus\UpdateRequest;
use App\Models\Admin\Menu;
use App\Repositories\Admin\MenuRepository;

class MenusController extends Controller
{
    /**
     * @var Tree
     */
    private $tree;

    public function __construct(MenuRepository $menuRepository, Tree $tree)
    {
        parent::__construct();
        $this->repository = $menuRepository;
        $this->tree       = $tree;
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
        $parents = $this->repository->findAll(['status:neq' => Menu::STATUS_DELETE]);
        $group   = $this->tree->init([
            'parentIdName' => 'parent',
            'childrenName' => 'children',
            'array'        => $parents,
        ])->getTree(0, '<option value="{id}">{extend_space}{name}</option>');

        // 载入视图
        return view('admin::menus.index', compact('status', 'parents', 'group'));
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
