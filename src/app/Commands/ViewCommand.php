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

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class View 用来生成 视图
 *
 * @package App\Commands
 */
class ViewCommand extends Command
{
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
    protected $description = '生成 view {--table=} 指定表 
     {--path=} 默认为项目 app/resource/views/admin ; 相对路径也是 app/resource/views/admin 开始; 绝对路径就是指定路径
     {--q=} (true|false) 是否询问编辑和搜索字段；默认不询问';

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
     * 获取目录
     *
     * @param  string $file_name 文件名称
     *
     * @return string
     */
    protected function getPath($file_name)
    {
        // 处理为model 的路径
        if (($path = $this->option('path')) && !starts_with($path, '/')) {
            $path = base_path('resources/views/admin/' . $path);
        } else if (is_empty($path)) {
            $path = base_path('resources/views/admin');
        }

        if (!file_exists($path)) {
            @mkdir($path, 0755, true);
        }

        return rtrim($path, '/') . '/' . ltrim($file_name, '/');
    }

    public function handle()
    {
        if (!$table = $this->option('table')) {
            $this->error('请输入表名称');
            return;
        }

        if (!$tables = DB::select('SHOW TABLES like "' . $table . '"')) {
            $this->error('表不存在');
            return;
        }

        // 查询表结构
        $structure = DB::select('SHOW FULL COLUMNS FROM `' . $table . '`');
        if ($file_name = $this->getPath('index.blade.php')) {
            if (file_exists($file_name) && !$this->confirm($file_name . ' 已经存在,是否覆盖？')) {
                return;
            }
        }

        $question = $this->option('q') == 'true';
        $html     = $this->createView($structure, $question);
        file_put_contents($file_name, $html);
        $this->info($file_name . ' 处理成功');
    }

    /**
     * @param      $array
     * @param bool $question
     *
     * @return string
     */
    private function createView($array, $question = false)
    {
        $primary_key = 'id';
        $strHtml     = $strWhere = '';
        if ($array) {
            foreach ($array as $value) {
                $field = array_get($value, 'Field');
                $title = array_get($value, 'Comment', $field);
                if (array_get($value, 'Key') == 'PRI') {
                    $primary_key = $field;
                }

                $html = "\t\t\t{title:\"{$title}\",data:\"{$field}\",";

                // 编辑
                if ($question && !in_array($field, [
                        'id',
                        'created_at',
                        'updated_at'
                    ]) && $this->confirm($field . '字段是否需要编辑?')) {
                    $html .= 'edit:{type: "text"}';
                }

                if (!in_array($field, $this->sortFields)) {
                    $html .= 'orderable:false,';
                }

                // 搜索
                if ($question && !in_array($field, [
                        'created_at',
                        'updated_at'
                    ]) && $this->confirm($field . '字段是否需要搜索?')) {
                    $html .= 'search:{type: "text"}';
                }

                $strHtml .= trim($html, ', ') . "}, \n";
            }
        }

        $strHtml            = rtrim($strHtml, " \n");
        $primary_key_config = $primary_key != 'id' ? 'pk: "' . $primary_key . '",' : '';
        $sHtml              = <<<html
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
    
    $(function () {
        var meTable = meTables({
            sTable: "#example2",
            searchType: "middle",
            checkbox: null,
            {$primary_key_config}
            table: {
                columns: [
                    {$strHtml}
                    {
                        "title": "操作",
                        "data": null,
                        "orderable": false,
                        "createdCell": meTables.handleOperator
                    }
                ]
            }
        });
    });
    </script>
@endpush
html;
        return $sHtml;
    }

}