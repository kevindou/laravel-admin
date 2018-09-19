<?php

namespace App\Http\Controllers\Admin\Slide;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Slide\SlideType\DestroyRequest;
use App\Http\Requests\Admin\Slide\SlideType\StoreRequest;
use App\Http\Requests\Admin\Slide\SlideType\UpdateRequest;
use App\Repositories\Slide\SlideTypeRepository;

/**
 * Class SlideTypesController
 * @package App\Http\Controllers\Admin\Slide
 */
class SlideTypesController extends Controller
{
    public function __construct(SlideTypeRepository $repository)
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
        return view('admin::slide.slide_types.index');
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