<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Menus\DestroyRequest;
use App\Http\Requests\Admin\Menus\StoreRequest;
use App\Http\Requests\Admin\Menus\UpdateRequest;
use App\Models\Admin\Menu;
use App\Repositories\Admin\MenuRepository;
use Illuminate\Support\Facades\DB;

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
        $parents = DB::table('menus')->where([
            ['status', '!=', Menu::STATUS_DELETE],
            ['parent', '=', 0]
        ])->pluck('name', 'id');

        // 载入视图
        return view('admin::menus.index', compact('status', 'parents'));
    }

    /**
     * 处理查询参数配置
     *
     * @return array
     */
    public function where()
    {
        return [
            'name'   => 'like',
            'url'    => 'like',
            'status' => 'in',
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
        return $this->sendJson($this->repository->update($id, $data));
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
