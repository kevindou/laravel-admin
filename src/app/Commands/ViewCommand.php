<?php
/**
 *
 * Model.php
 *
 * Author: jinxing.liu@verystar.cn
 * Create: 2018/6/27 14:13
 * Editor: created by PhpStorm
 */

namespace App\Commands;

use App\Traits\Command\CommandTrait;

/**
 * Class View 用来生成 视图
 *
 * @package App\Commands
 */
class ViewCommand extends AdminCommand
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:view {--table=} {--path=} {--q=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成 view 
     {--table=} 指定表名称 
     {--path=}  指定目录 [ 没有传递绝对路径，否则使用相对对路径 从 resource/views 开始 ]';

    /**
     * @var array 允许排序字段
     */
    private $sortFields = [
        'id',
        'sort',
        'created_at',
        'updated_at'
    ];

    /**
     * @var string 指定目录
     */
    protected $basePath = 'resources/views';

    public function handle()
    {
        if (!$table = $this->option('table')) {
            $this->error('请输入表名称');
            return;
        }

        if (!$this->findTableExist($table)) {
            $this->error('表不存在');
            return;
        }

        // 查询表结构
        $file_name = $this->getPath('index.blade.php');
        $this->rendView($file_name, $this->findTableStructure($table));
    }

    /**
     * 渲染视图文件
     *
     * @param string $file_name
     * @param  array $array
     *
     * @return string
     */
    private function rendView($file_name, $array)
    {
        $primary_key = 'id';
        $strHtml     = $strWhere = '';
        if ($array) {
            foreach ($array as $value) {
                $field = array_get($value, 'Field');
                $title = array_get($value, 'Comment', $field);
                $html  = "\t\t\t\t{
                \t title: \"{$title}\",\n\t\t\t\t\t data: \"{$field}\",\n";

                // 编辑
                if (array_get($value, 'Key') == 'PRI') {
                    $primary_key = $field;
                    $html        .= "\t\t\t\t\t edit: {type: \"hidden\"},\n";
                } elseif (!in_array($field, ['created_at', 'updated_at'])) {
                    $params = ['type: "text"'];
                    // 不能为空
                    if (array_get($value, 'Null') == 'NO') {
                        $params[] = 'required: true';
                    }

                    // 类型处理
                    $type = array_get($value, 'Type');
                    if ($this->isInt($type)) {
                        $params[] = 'number: true';
                    } elseif ($string = $this->isString($type)) {
                        $min = array_get($string, 'min', 2);
                        $max = array_get($string, 'max');
                        if (count($string) == 2) {
                            $params[] = "rangelength: [{$min}, {$max}]";
                        } elseif ($min) {
                            $params[] = 'minlength: ' . $min;
                        } elseif ($max) {
                            $params[] = 'maxlength: ' . $max;
                        }
                    }

                    $html .= "\t\t\t\t\t edit: {\n\t\t\t\t\t\t " . implode(",\n\t\t\t\t\t\t", $params) . "\n\t\t\t\t\t}\n";
                }

                $strHtml .= trim($html, ', ') . "\t\t\t\t}, \n";
            }
        }

        $columns     = rtrim($strHtml, " \n");
        $primary_key = $primary_key != 'id' ? 'pk: "' . $primary_key . '",' : '';
        return $this->render($file_name, compact('columns', 'primary_key'));
    }

    public function getRenderHtml()
    {
        return <<<html
@extends('admin::layouts.admin')
@section("main-content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12" style="margin-bottom: 20px;" id="me-table-search-form">
                            <button class="btn btn-success btn-sm pull-left me-table-create">
                                {{ trans('admin.create') }}
                            </button>
                        </div>
                        <div class="col-sm-12">
                            <table id="example2" class="table table-bordered table-hover"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin::common.datatable')
@push("script")
    <script>    
    \$(function () {
        var meTable = meTables({
            sTable: "#example2",
            searchType: "middle",
            checkbox: null,
            {primary_key}
            table: {
                columns: [
                    {columns}
                    {
                        title: "操作",
                        data: null,
                        orderable: false,
                        createdCell: meTables.handleOperator
                    }
                ]
            }
        });
    });
    
    /**
    meTables.fn.extend({
        // 显示的前置和后置操作
        beforeShow: function(data, child) {
            return true;
        },
        afterShow: function(data, child) {
            return true;
        },
        
        // 编辑的前置和后置操作
        beforeSave: function(data, child) {
            return true;
        },
        afterSave: function(data, child) {
            return true;
        }
    });
    */
    </script>
@endpush
html;
    }
}