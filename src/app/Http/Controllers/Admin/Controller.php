<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Traits\JsonTrait;
use Illuminate\Support\Facades\DB;

/**
 * Class Controller 后台基础控制器
 *
 * @package App\Http\Controllers\Admin
 */
class Controller extends \App\Http\Controllers\Controller
{
    /**
     * 引入json 处理
     */
    use JsonTrait;

    /**
     * @var string 定义使用model
     */
    protected $model = '';

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
     * 处理查询配置信息
     *
     * @return array
     */
    protected function where()
    {
        return [];
    }

    /**
     * 数据搜索处理
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $request = request();
        // 查询的分页信息
        $start  = $request->input('start');
        $length = $request->input('length');

        $orderBy = $request->input('order');
        $columns = $request->input('columns');

        // 处理排序
        $order = [];
        if ($orderBy) {
            foreach ($orderBy as $value) {
                $key = $value['column'];
                if (!empty($columns[$key]) && !empty($columns[$key]['data'])) {
                    $order[$columns[$key]['data']] = $value['dir'];
                }
            }
        }

        parse_str($request->input('where'), $array);
        $query = DB::table((new $this->model)->getTable());
        Helper::handleWhere($query, $array, $this->where($array));
        $total = $query->count();

        // 排序
        foreach ($order as $key => $value) {
            $query->orderBy($key, $value);
        }

        // 返回结果
        return $this->returnJson([
            'draw'            => $request->input('draw'),
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $query->offset($start)->limit($length)->get(),
            'code'            => 0,
            'params'          => $request->input('params'),
            'sql'             => $query->toSql(),
        ]);
    }

    /**
     * 创建数据
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return $this->save(new $this->model);
    }

    /**
     * 修改数据信息
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update()
    {
        return $this->save($this->findOrFail(), 1007);
    }

    /**
     * 删除数据
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete()
    {
        $model = $this->findOrFail();
        if ($model->delete()) {
            return $this->success($model);
        } else {
            return $this->error(1006);
        }
    }

    /**
     * 查询model
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    protected function findOrFail()
    {
        /* @var $model \Illuminate\Database\Eloquent\Model */
        $model = new $this->model;
        $id    = request()->input($model->getKeyName());
        if (!$id) {
            throw new \UnexpectedValueException('请求数据为空');
        }

        return $model::findOrFail($id);
    }

    /**
     * 数据编辑和创建
     *
     * @param \Illuminate\Database\Eloquent\Model $model model 对象
     * @param int $error 错误的错误码默认1005
     * @return \Illuminate\Http\JsonResponse
     */
    protected function save($model, $error = 1005)
    {
        return $model->fill($this->handleRequest())->save() ? $this->success($model) : $this->error($error);
    }

    /**
     * 处理请求参数
     *
     * @return array|string
     */
    protected function handleRequest()
    {
        return request()->input();
    }
}
