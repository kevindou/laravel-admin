<?php
/**
 *
 * Repositroy.php
 *
 * Author: jinxing.liu@verystar.cn
 * Create: 2018/6/13 14:43
 * Editor: created by PhpStorm
 */

namespace App\Repositories;

use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Repository
{
    use ResponseTrait;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var int 查询数据最多查询10000，防止内存溢出
     */
    protected $max_collection_size = 10000;

    protected $expression = [
        'eq'          => '=',
        'neq'         => '!=',
        'ne'          => '!=',
        'gt'          => '>',
        'egt'         => '>=',
        'gte'         => '>=',
        'ge'          => '>=',
        'lt'          => '<',
        'le'          => '<=',
        'lte'         => '<=',
        'elt'         => '<=',
        'in'          => 'In',
        'not_in'      => 'NotIn',
        'not in'      => 'NotIn',
        'between'     => 'Between',
        'not_between' => 'NotBetween',
        'not between' => 'NotBetween',
        'like'        => 'LIKE',
        'not_like'    => 'NOT LIKE',
        'not like'    => 'NOT LIKE'
    ];

    /**
     * 获取model
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * 创建数据
     *
     * @param $data
     *
     * @return array
     */
    public function create($data)
    {
        if (!is_array($data) || !$data) {
            return $this->error('操作失败');
        }
        if (!$model = $this->model->create($data)) {
            return $this->error('操作失败');
        }

        return $this->success($model->toArray());
    }

    public function update($condition, $update_data)
    {
        // 没有添加更新条件或者修改条件
        if (empty($condition) || empty($update_data)) {
            return $this->error('更新失败');
        }

        $condition = $this->getPrimaryKeyCondition($condition);

        // 不能更新主键
        if (array_get($update_data, $this->model->getKeyName())) {
            unset($update_data[$this->model->getKeyName()]);
        }

        // 不能修改不存在的字段
        $columns = $this->getTableColumns();
        foreach ($update_data as $column => $value) {
            if (!isset($columns[$column])) {
                unset($update_data[$column]);
            }
        }

        if (empty($update_data)) {
            return $this->error('没有需要更新数据信息');
        }

        try {
            $model = $this->model->newInstance();
            $rows  = $model->where($condition)->update($model->fill($update_data)->getAttributes());
            // 更新成功要调用清除缓存方法
            if ($rows && method_exists($this, 'clearCache')) {
                $this->clearCache($condition);
            }

            return $this->success($rows, '更新成功');
        } catch (\Exception $e) {
            return $this->error($this->getError($e));
        }
    }

    /**
     * 删除数据
     *
     * @param array|mixed $condition 查询条件
     *
     * @return array
     */
    public function delete($condition)
    {
        $condition = $this->getPrimaryKeyCondition($condition);
        try {
            if (method_exists($this, 'clearCache')) {
                $this->clearCache($condition);
            }

            $return = $this->model->where($condition)->delete();
            return $this->success($return);
        } catch (\Exception $e) {
            return $this->error($this->getError($e));
        }
    }

    /**
     * 获取主键查询条件
     *
     * @param $condition
     *
     * @return array
     */
    public function getPrimaryKeyCondition($condition)
    {
        if (is_scalar($condition)) {
            if ($this->model->getKeyType() == 'int') {
                $condition = intval($condition);
            }

            $condition = [
                $this->model->getKeyName() => $condition
            ];
        }

        return (array)$condition;
    }

    /**
     *
     * 获取表格字段，并转换为KV格式
     *
     * @param $model
     *
     * @return array
     */
    public function getTableColumns($model = '')
    {
        $model    = $model && is_object($model) ? $model : $this->model;
        $_columns = [];
        foreach ($model->columns as $column) {
            $_columns[$column] = $column;
        }

        return $_columns;
    }

    /**
     *
     * 根据运行环境上报错误
     *
     * @param \Exception $e
     *
     * @return mixed|string|\Symfony\Component\Translation\TranslatorInterface
     */
    private function getError(\Exception $e)
    {
        // 记录数据库执行错误日志
        logger()->error('db error', ['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
        return app()->environment('production') ? '系统错误，请重试' : $e->getMessage();
    }

    /**
     * @param        $condition
     * @param string $fields
     *
     * @return array
     */
    public function findOne($condition, $fields = '*')
    {
        if ($one = $this->setModelCondition($condition, $fields)->first()) {
            /* @var $one Collection */
            return $one->toArray();
        }

        return [];
    }

    public function findAll($condition, $fields = '*')
    {
        if ($all = $this->setModelCondition($condition, $fields)->get()) {
            /* @var $all Collection */
            return $all->toArray();
        }

        return [];
    }

    public function findOneBySql($sql, $binds = [], $connection = 'default')
    {

    }

    public function findAllBySql($sql, $binds = [], $connection = 'default')
    {

    }

    /**
     * 查询字段信息
     *
     * @param  mixed $query
     * @param array  $fields
     * @param        $table
     *
     * @param array  $columns
     *
     * @return mixed
     */
    private function select($query, $fields = ['*'], $table, $columns = [])
    {
        $select     = [];
        $use_select = true;
        foreach ($fields as $i => $field) {
            if (is_int($i) && is_string($field)) {
                $select[] = isset($columns[$field]) ? $table . '.' . $field : $field;
                if (substr($field, -6) === '_count') {
                    $use_select = false;
                    break;
                }
            }
        }

        if ($use_select) {
            return $query->select($select);
        }

        return $query;
    }

    /**
     * @param mixed $query
     * @param       $order_by
     * @param       $table
     * @param       $columns
     *
     * @return mixed
     */
    private function orderBy($query, $order_by, $table, $columns)
    {
        if ($orders = explode(',', $order_by)) {
            foreach ($orders as $order) {
                list($k, $v) = array_pad(explode(' ', preg_replace('/\s+/', ' ', $order)), 2, null);
                if ($k && isset($columns[$k]) && in_array(strtolower($v), ['', 'asc', 'desc'])) {
                    $query = $query->orderBy($table ? $table . '.' . $k : $k, $v ?: 'desc');
                }
            }
        }

        return $query;
    }

    /**
     * @param array  $condition
     * @param mixed  $query
     * @param string $table
     * @param array  $columns
     *
     * @return mixed
     */
    protected function handleConditionQuery($condition, $query, $table, $columns)
    {
        // 设置了排序
        if ($order_by = array_get($condition, 'orderBy')) {
            $query = $this->orderBy($query, $order_by, $table, $columns);
            unset($condition['orderBy']);
        }

        // 设置了limit
        if ($limit = array_get($condition, 'limit')) {
            $query = $query->limit(intval($limit));
            unset($condition['limit']);
        }

        // 设置了offset
        if ($offset = array_get($condition, 'offset')) {
            $query->offset(intval($offset));
            unset($condition['offset']);
        }

        // 设置了分组
        if ($groupBy = array_get($condition, 'groupBy')) {
            unset($condition['groupBy']);
        }

        // 没有查询条件直接退出
        if (empty($condition)) {
            return $query;
        }

        return $this->conditionQuery($condition, $query, $table, $columns);
    }

    /**
     * @param        $condition
     * @param  mixed $query
     * @param        $table
     * @param        $columns
     * @param bool   $or
     *
     * @return Model|mixed
     */
    protected function conditionQuery($condition, $query, $table, $columns, $or = false)
    {
        foreach ($condition as $column => $bind_value) {
            // or 查询
            if (strtolower($column) === 'or' && is_array($bind_value) && $bind_value) {
                $query = $query->where(function ($query) use ($bind_value, $table, $columns) {
                    $this->conditionQuery($bind_value, $query, $table, $columns, true);
                });

                continue;
            }


            // 字段直接查询 field1 => value1
            if (isset($columns[$column])) {
                $this->handleFieldQuery($query, $table . '.' . $column, $bind_value, $or);
                continue;
            }

            // 表达式查询 field1:neq => value1
            list($field, $expression) = array_pad(explode(':', $column, 2), 2, null);
            if ($field && $expression) {
                $this->handleExpressionConditionQuery($query, [$table . '.' . $field, $expression, $bind_value], $or);
                continue;
            }

            // 自定义 scope 查询
            if (is_a($query, Model::class)) {
                $strMethod = 'scope' . ucfirst($column);
                if (!method_exists($query, $strMethod)) {
                    $strMethod = 'scope' . ucfirst(camel_case($column));
                    $strMethod = method_exists($query, $strMethod) ? $strMethod : null;
                }

                if ($strMethod) {
                    $query->{$strMethod}($query, $bind_value);
                }

                continue;
            }

            // scope 自定义查询
            try {
                $query->{$column}($bind_value);
            } catch (\Exception $e) {
                try {
                    $column = ucfirst(camel_case($column));
                    $query->{$column}($bind_value);
                } catch (\Exception $e) {

                }
            }
        }

        return $query;
    }

    /**
     * @param Model $query
     * @param array $condition 查询对象
     *                         ['field', 'expression', 'value']
     * @param bool  $or
     *
     * @return Model
     */
    protected function handleExpressionConditionQuery($query, $condition = [], $or = false)
    {
        list($column, $expression, $value) = $condition;
        if ($expression = array_get($this->expression, strtolower($expression))) {
            if (in_array($expression, ['In', 'NotIn', 'Between', 'NotBetween'])) {
                $strMethod = $or ? 'orWhere' . $expression : 'where' . $expression;
                $query->{$strMethod}($column, $value);
            }

            $strMethod = $or ? 'orWhere' : 'where';
            if (in_array($expression, ['LIKE', 'NOT LIKE'])) {
                $value = '%' . (string)$value . '%';
            }

            $query->{$strMethod}($column, $expression, $value);
        }

        return $query;
    }

    /**
     * 字段查询
     *
     * @param      $query
     * @param      $field
     * @param      $value
     *
     * @param bool $or
     *
     * @return mixed
     */
    protected function handleFieldQuery($query, $field, $value, $or = false)
    {
        $strMethod = is_array($value) ? 'whereIn' : 'where';
        if ($or) {
            $strMethod = 'or' . ucfirst($strMethod);
        }

        return $query->{$strMethod}($field, $value);
    }

    /**
     * @param array $conditions
     * @param array $fields
     *
     * @return Model|mixed
     */
    public function setModelCondition($conditions = [], $fields = [])
    {
        $model   = $this->model;
        $table   = $this->model->getTable();
        $columns = $this->getTableColumns($model);

        // 查询条件
        if (!$conditions = $this->getPrimaryKeyCondition($conditions)) {
            return $this->model;
        }

        $fields = (array)$fields;

        // 分组，如果是relation的查询条件，需要放在前面build
        $relation_condition = $model_condition = [];
        foreach ($conditions as $field => $value) {
            list($relation_name, $relation_key) = array_pad(explode('.', $field, 2), 2, null);
            if ($relation_name && $relation_key) {
                $relation_condition[$field] = $value;
            } else {
                $model_condition[$field] = $value;
            }
        }

        // 首先设置relation查询，不能放在后面执行
        if ($relations = $this->getRelations($model, $fields, $relation_condition)) {
            $model = $model->with($relations);
        }

        // 判断是否有关联模型的统计操作
        $model = $this->addRelationCountSelect($model, $fields, $relation_condition);
        unset($relations, $relation_name, $relation_filters);

        $model = $this->select($model, $fields, $table, $columns);
        return $this->handleConditionQuery($model_condition, $model, $table, $columns);
    }

    /**
     * 获取关联表集合
     *
     * @param $model
     * @param $fields
     * @param $relation_condition
     *
     * @return array
     */
    protected function getRelations($model, $fields = [], $relation_condition)
    {
        // relation查询时，合并SQL，优化语句
        $relations = [];
        if ($fields) {
            foreach ($fields as $field_key => $field_val) {
                if (!is_int($field_key)) {
                    // Model对象
                    if (method_exists($model, ucfirst($field_key))) {
                        $relations[$field_key] = [];
                    }
                }
            }

            unset($field_key, $field_val);
        }

        // 关联查询 roles.user_id = 1
        if ($relation_condition) {
            foreach ($relation_condition as $relation_key => $relation_value) {
                $dot_index = strpos($relation_key, '.');
                if ($dot_index !== false) {
                    $relation_name  = substr($relation_key, 0, $dot_index);
                    $relation_field = substr($relation_key, $dot_index + 1);
                } else {
                    $relation_name  = '';
                    $relation_field = $relation_key;
                }

                // 如果relation存在
                if (method_exists($model, $relation_name)) {
                    // 未初始化时先初始化为空数组
                    if (!isset($relations[$relation_name])) {
                        $relations[$relation_name] = [];
                    }
                    $relations[$relation_name][$relation_field] = $relation_value;
                }
            }
        }

        // 当前model实际要绑定的relation
        $bind_relations = [];
        if ($relations) {
            foreach ($relations as $relation_name => $relation_filters) {
                $bind_relations[$relation_name] = $this->buildRelation(
                    array_merge(
                        $this->getRelationDefaultFilters($model, $relation_name),
                        (array)$relation_filters
                    ),
                    (array)array_get($fields, $relation_name)
                );
            }
        }

        return $bind_relations;
    }

    /**
     * @param $model
     * @param $relation_name
     *
     * @return array
     */
    private function getRelationDefaultFilters($model, $relation_name)
    {
        // 添加relation的默认条件，默认条件数组为“$relationFilters"的public属性
        $filter_attribute = $relation_name . 'Filters';
        if (isset($model->$filter_attribute) && is_array($model->$filter_attribute)) {
            return $model->$filter_attribute;
        }

        $relation_data = [];
        try {
            //由于PHP类属性区分大小写，而relation_count字段为小写，利用反射将属性转为小写，再进行比较
            if (!$pros = (new \ReflectionClass($model))->getDefaultProperties()) {
                foreach ($pros as $name => $val) {
                    if (strtolower($name) == strtolower($filter_attribute) && is_array($val)) {
                        $relation_data = $val;
                    }
                }
            }
        } catch (\Exception $e) {

        }

        return $relation_data;
    }

    /**
     * @param $relation_filters
     * @param $relation_fields
     *
     * @return \Closure
     */
    private function buildRelation($relation_filters, $relation_fields)
    {
        return function ($query) use ($relation_filters, $relation_fields) {
            // 获取relation的表字段
            /* @ver $model Model */
            $model   = $query->getRelated();
            $columns = $this->getTableColumns($model);
            $table   = $model->getTable();

            // relation绑定
            if ($relations = $this->getRelations($model, $relation_fields, $relation_filters)) {
                $query = $query->with($relations);
            }

            // 判断是否有关联模型的统计操作
            if ($relation_fields) {
                $this->addRelationCountSelect($query, $relation_fields, $relation_filters);
            }

            $this->select($query, $relation_fields, $table);
            $this->handleConditionQuery($relation_filters, $query, $table, $columns);
        };
    }

    /**
     * @param mixed $model
     * @param       $fields
     * @param       $relation_filters
     *
     * @return mixed
     */
    private function addRelationCountSelect($model, $fields, $relation_filters)
    {
        $filters = [];
        if ($relation_filters) {
            foreach ($relation_filters as $filter => $value) {
                list($a, $b) = array_pad(explode('.', $filter, 2), 2, null);
                if ($a && $b) {
                    $a = strtolower($a);
                    if (!isset($filters[$a])) {
                        $filters[$a] = [];
                    }
                    $filters[$a][$b] = $value;
                }
            }

            unset($filter, $value);
        }

        $relations_count = [];
        if ($fields) {
            foreach ($fields as $__k => $__f) {
                if (is_int($__k) && is_string($__f)) {
                    $count_key     = substr($__f, -6);
                    $relation_name = strtolower(substr($__f, 0, -6));
                    if ($count_key == '_count' && method_exists($model->getModel(), $relation_name)) {
                        // 当前模型的关联模型
                        $sub_model = $model->getModel()->$relation_name()->getRelated();
                        $columns   = $this->getTableColumns($sub_model);
                        $table     = $sub_model->getTable();

                        // 关联模型的查询条件
                        $cur_filters = array_merge(
                            $this->getRelationDefaultFilters($model->getModel(), $relation_name),
                            (array)array_get($filters, $relation_name)
                        );

                        $relations_count[$relation_name] = function ($query) use ($cur_filters, $columns, $table) {
                            return $this->handleConditionQuery($cur_filters, $query, $table, $columns);
                        };
                    }
                }

                unset($count_key, $relation_name, $__k, $__f, $sub_model, $columns, $table);
            }
        }

        if ($relations_count) {
            $model = $model->withCount($relations_count);
        }

        return $model;
    }
}