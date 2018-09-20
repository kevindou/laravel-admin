<?php
     
namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\Article\ArticleTypes\DestroyRequest;
use App\Http\Requests\Admin\Article\ArticleTypes\StoreRequest;
use App\Http\Requests\Admin\Article\ArticleTypes\UpdateRequest;
use App\Repositories\Article\ArticleTypesRepository;

class ArticleTypesController extends Controller
{
    /**
     * @var ArticleTypesRepository
     */
    private $articleTypesRepository;
    
    public function __construct(ArticleTypesRepository $articleTypesRepository)
    {
        parent::__construct();
        $this->repository = $articleTypesRepository;
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::article.article_types.index');
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