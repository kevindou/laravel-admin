<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Common\UploadImageRequest;
use App\Repositories\Repository;
use App\Traits\JsonTrait;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

/**
 * Class Controller 后台基础控制器
 *
 * @package App\Http\Controllers\Admin
 */
class Controller extends BaseController
{
    /**
     * 引入json 处理
     */
    use JsonTrait, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * 初始化配置使用中间件
     *
     * Controller constructor.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * 获取查询的 model
     *
     * @param array|mixed $condition 查询条件
     *
     * @param array       $fields 查询的字段信息
     *
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function findModel($condition, $fields = ['*'])
    {
        return $this->repository->getFilterModel($condition, $fields);
    }

    /**
     * 数据搜索处理
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        // 处理请求参数
        $offset    = (int)request()->input('offset', 0);
        $limit     = (int)request()->input('limit', 10);
        $draw      = (int)request()->input('draw', 1);
        $fields    = request()->input('columns', []);
        $condition = request()->input('filters', []);

        // 查询数据
        $query = $this->findModel(array_merge($condition, ['orderBy' => request()->input('orderBy')]), $fields);
        $total = $query->count();

        // 返回结果
        return $this->returnJson([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $query->offset($offset)->limit($limit)->get(),
            'code'            => 0,
        ]);
    }

    /**
     * 图片上传处理
     *
     * @param UploadImageRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(UploadImageRequest $request)
    {
        $file = $request->file('vue_image');
        if (!$file->isValid()) {
            return $this->error(1001);
        }

        // 上传文件
        if (!$url = $file->store(date('Ymd'))) {
            return $this->error(1004);
        }

        // 新增数据
        return $this->success([
            'name' => $file->getClientOriginalName(),
            'url'  => Storage::url($url),
        ]);
    }
}
