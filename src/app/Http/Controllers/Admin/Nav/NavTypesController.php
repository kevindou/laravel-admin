<?php

namespace App\Http\Controllers\Admin\Nav;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Nav\NavType\StoreRequest;
use App\Http\Requests\Admin\Nav\NavType\UpdateRequest;
use App\Http\Requests\Admin\Nav\NavType\DestroyRequest;
use App\Repositories\Nav\NavTypeRepository;

/**
 * Class NavTypesController
 * @package App\Http\Controllers\Admin\Nav
 */
class NavTypesController extends Controller
{
    public function __construct(NavTypeRepository $repository)
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
        return view('admin::nav.nav_types.index');
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
        return $this->sendJson($this->repository->update($request->input('type_id'), $request->all()));
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
        return $this->sendJson($this->repository->delete($request->input('type_id')));
    }
}
