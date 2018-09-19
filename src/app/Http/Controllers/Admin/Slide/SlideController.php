<?php

namespace App\Http\Controllers\Admin\Slide;

use App\Helpers\Tree;
use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Slide\Slide\DestroyRequest;
use App\Http\Requests\Admin\Slide\Slide\StoreRequest;
use App\Http\Requests\Admin\Slide\Slide\UpdateRequest;
use App\Repositories\Slide\SlideRepository;
use App\Repositories\Slide\SlideTypeRepository;

class SlideController extends Controller
{
    /**
     * @var SlideTypeRepository
     */
    private $slideTypeRepository;
    /**
     * @var Tree
     */
    private $tree;

    public function __construct(SlideRepository $repository, SlideTypeRepository $slideTypeRepository, Tree $tree)
    {
        parent::__construct();
        $this->repository          = $repository;
        $this->slideTypeRepository = $slideTypeRepository;
        $this->tree                = $tree;
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $types = $this->slideTypeRepository->findAllToIndex(['status' => 1], 'type_id', 'name');
        return view('admin::slide.slide.index', compact('types'));
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