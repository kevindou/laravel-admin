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
class ModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:model {--table=} {--path=} {--r=} {--name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成 model {--table=} 指定表 
     {--path=} 默认为项目 app/Models ; 相对路径也是 app/Models 开始; 绝对路径就是指定路径 
     {--r=} (true|false) 是否需要生成Repositories 默认生成
     {--name=} 生成 model 名称 ';

    /**
     * 获取目录
     *
     * @param        $path
     *
     * @param string $type
     *
     * @return string
     */
    protected function getPath($path, $type = 'model')
    {
        $path = rtrim($path, '/') . '/';
        if ($type != 'model') {
            $path = str_replace('Models', 'Repositories', $path);
        }

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }


    public function handle()
    {
        // 处理为model 的路径
        if (($path = $this->option('path')) && !starts_with($path, '/')) {
            $path = base_path('app/Models/' . $path);
        } else if (is_empty($path)) {
            $path = base_path('app/Models');
        }

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

        $primaryKey = 'id';
        $columns    = "[\n";
        foreach ($structure as $column) {
            $field = array_get($column, 'Field');
            if (array_get($column, 'Key') === 'PRI') {
                $primaryKey = $field;
            }

            $columns .= "\t\t'{$field}',\n";
        }

        $columns   .= "\t]";
        $arr_path  = explode('/', trim($this->getPath($path), '/'));
        $namespace = implode('\\', array_slice($arr_path, array_search('Models', $arr_path) + 1));
        if ($namespace) {
            $namespace = '\\' . $namespace;
        }

        $str = str_replace([
            '{model_name}',
            '{table}',
            '{primaryKey}',
            '{columns}',
            '{namespace}'
        ], [
            $model_name,
            $table,
            $primaryKey,
            $columns,
            $namespace,
        ], $this->template);

        $file_name = $this->getPath($path) . $model_name . '.php';
        if (!file_exists($file_name) || $this->confirm($file_name . ' 已经存在，是否需要覆盖?')) {
            file_put_contents($file_name, $str);
            $this->info('处理成功:' . $file_name);
        } else {
            $this->info($file_name . ' 文件已经存在');
        }

        if ($this->option('r') == 'false') {
            $this->info('处理成功');
            return;
        }

        // 写入Repository
        $repository_file = $this->getPath($path, 'repository') . $model_name . 'Repository.php';
        if (!file_exists($repository_file) || $this->confirm($repository_file . ' 已经存在，是否需要覆盖?')) {
            file_put_contents(
                $repository_file,
                str_replace(['{namespace}', '{model_name}'], [$namespace, $model_name], $this->repositoriesTemplate)
            );

            $this->info('处理成功:' . $repository_file);
        } else {
            $this->info($repository_file . ' 文件已经存在');
        }
    }

    private $template = <<<html
<?php

namespace App\Models{namespace};

use App\Models\Model;

class {model_name} extends Model
{
    protected \$table      = '{table}';
    protected \$primaryKey = '{primaryKey}';
    public    \$columns    = {columns};
}
html;

    private $repositoriesTemplate = <<<html
<?php

namespace App\Repositories{namespace};

use App\Models{namespace}\{model_name};
use App\Repositories\Repository;

class {model_name}Repository extends Repository
{
    public function __construct({model_name} \$model)
    {
        \$this->model = \$model;
    }
}
html;

}