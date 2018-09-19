<?php

namespace App\Http\Controllers\Admin\Nav;

use App\Helpers\Tree;
use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Nav\Nav\DestroyRequest;
use App\Http\Requests\Admin\Nav\Nav\StoreRequest;
use App\Http\Requests\Admin\Nav\Nav\UpdateRequest;
use App\Repositories\Nav\NavRepository;
use App\Repositories\Nav\NavTypeRepository;

/**
 * Class WebHooksController
 * @package App\Http\Controllers\Admin
 */
class NavController extends Controller
{
    /**
     * @var NavTypeRepository
     */
    private $navTypeRepository;
    /**
     * @var Tree
     */
    private $tree;

    public function __construct(
        NavRepository $repository,
        NavTypeRepository $navTypeRepository,
        Tree $tree
    )
    {
        parent::__construct();
        $this->repository        = $repository;
        $this->navTypeRepository = $navTypeRepository;
        $this->tree              = $tree;
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $types   = $this->navTypeRepository->findAllToIndex(['status' => 1], 'type_id', 'name');
        $parents = $this->repository->findAll(['status' => 1]);
        $group   = $this->tree->init([
            'parentIdName' => 'parent_id',
            'childrenName' => 'children',
            'array'        => $parents,
        ])->getTree(0, '<option value="{id}">{extend_space}{name}</option>');
        return view('admin::nav.nav.index', compact('types', 'group', 'parents'));
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
