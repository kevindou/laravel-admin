<?php

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Article\Articles\DestroyRequest;
use App\Http\Requests\Admin\Article\Articles\StoreRequest;
use App\Http\Requests\Admin\Article\Articles\UpdateRequest;
use App\Repositories\Article\ArticlesRepository;
use App\Repositories\Article\ArticleTypesRepository;

class ArticlesController extends Controller
{
    /**
     * @var ArticleTypesRepository
     */
    private $articleTypesRepository;

    public function __construct(ArticlesRepository $articlesRepository, ArticleTypesRepository $articleTypesRepository)
    {
        parent::__construct();
        $this->repository             = $articlesRepository;
        $this->articleTypesRepository = $articleTypesRepository;
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $types = $this->articleTypesRepository->findAllToIndex([
            'status'  => 1,
            'orderBy' => 'sort asc, type_id asc'
        ], 'type_id', 'name');
        return view('admin::article.articles.index', compact('types'));
    }

    public function create()
    {
        view()->share([
            'title'           => trans('添加文章信息'),
            '__active_menu__' => 'admin/article/articles/index'
        ]);

        $types = $this->articleTypesRepository->findAllToIndex([
            'status'  => 1,
            'orderBy' => 'sort asc, type_id asc'
        ], 'type_id', 'name');

        return view('admin::article.articles.create', compact('types'));
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
        // ajax 直接返回
        $result = $this->repository->create($request->all());
        if ($request->ajax()) {
            return $this->sendJson($result);
        }

        // 判断结果返回
        if ($result[0]) {
            return redirect('/admin/article/articles/index');
        }

        return back()->with('error', $result[1]);
    }

    /**
     * 显示编辑页面
     *
     * @param DestroyRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(DestroyRequest $request)
    {
        view()->share([
            'title'           => trans('编辑文章信息'),
            '__active_menu__' => 'admin/article/articles/index'
        ]);
        $types = $this->articleTypesRepository->findAllToIndex([
            'status'  => 1,
            'orderBy' => 'sort asc, type_id asc'
        ], 'type_id', 'name');
        $info  = $this->repository->findOne($request->get('id'));
        return view('admin::article.articles.edit', compact('types', 'info'));
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
        // ajax 直接返回
        $result = $this->repository->update($request->input('id'), $request->all());
        if ($request->ajax()) {
            return $this->sendJson($result);
        }

        // 判断结果返回
        if ($result[0]) {
            return redirect('/admin/article/articles/index');
        }

        return back()->with('error', $result[1]);
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