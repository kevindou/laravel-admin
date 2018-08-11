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
 * Class Model 用来生成model
 *
 * @package App\Commands
 */
class RequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:request {--table=} {--path=} {--name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成 request {--table=} 指定表 
     {--path=} 默认为项目 app/Http/Requests ; 相对路径也是 app/Http/Requests 开始; 绝对路径就是指定路径';

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
            $path = base_path('app/Http/Requests/' . $path);
        } else if (is_empty($path)) {
            $path = base_path('app/Http/Requests');
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
        if (!$model_name = $this->option('name')) {
            $model_name = ucfirst(camel_case(str_plural($table, 1)));
        }

    }
}