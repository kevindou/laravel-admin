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
 * Class Model 用来生成model
 *
 * @package App\Commands
 */
class ModelCommand extends AdminCommand
{
    use CommandTrait;

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
    protected $description = '生成 model 
    {--table=} 指定表名称 
    {--name}   指定名称，不指定使用table名称 [ 可以指定相对目录: Admin/User ]
    {--path=}  指定目录 [ 没有传递绝对路径，否则使用相对对路径 从 app/Models 开始 ]  
    {--r=}     指定是否需要生成Repositories 默认生成 [ --r=false 或者 --r=true ]';

    /**
     * @var string 生成目录
     */
    protected $basePath = 'app/Models';

    public function handle()
    {
        if (!$table = $this->option('table')) {
            $this->error('请输入表名称');
            return;
        }

        if (!$tables = $this->findTableExist($table)) {
            $this->error('表不存在');
            return;
        }

        $model_name = $this->handleOptionName(str_replace_last('s', '', str_plural($table, 1)));
        list($columns, $primaryKey) = $this->getColumnsAndPrimaryKey($table);
        $file_name = $this->getPath($model_name . '.php');
        list($namespace, $class_name) = $this->getNamespaceAndClassName($file_name, 'Models');

        // 生成文件
        $this->render($file_name, [
            'table'      => $table,
            'primaryKey' => $primaryKey,
            'columns'    => $columns,
            'class_name' => $class_name,
            'namespace'  => $namespace,
        ]);

        // 生成 repository
        if ($this->option('r') != 'false') {
            $model_class = $namespace ? trim(str_replace('\\', '/', $namespace), '/') . '/' : '';
            $model_class .= $class_name;
            $arguments   = [
                '--model' => $model_class,
                '--name'  => $model_class
            ];

            if (($path = $this->option('path')) && starts_with($path, '/')) {
                $array = explode('/', $path);
                if ($position = array_search('Models', $array)) {
                    $array = array_slice($array, 0, $position);
                    array_push($array, 'Repositories');
                }

                $arguments['--path'] = implode('/', $array);
            }

            $this->call('admin:repository', $arguments);
        }
    }

    /**
     * 获取主键和 columns 信息
     *
     * @param string $table
     *
     * @return array
     */
    protected function getColumnsAndPrimaryKey($table)
    {
        $structure  = $this->findTableStructure($table);
        $primaryKey = 'id';
        $columns    = "[\n";
        foreach ($structure as $column) {
            $field = array_get($column, 'Field');
            if (array_get($column, 'Key') === 'PRI') {
                $primaryKey = $field;
            }

            $columns .= "\t\t'{$field}',\n";
        }

        $columns .= "\t]";
        return [$columns, $primaryKey];
    }

    public function getRenderHtml()
    {
        return <<<html
<?php

namespace App\Models{namespace};

use App\Models\Model;

class {class_name} extends Model
{
    protected \$table      = '{table}';
    protected \$primaryKey = '{primaryKey}';
    public    \$columns    = {columns};
}
html;
    }
}