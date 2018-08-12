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

use App\Traits\CommandTrait;
use Illuminate\Support\Facades\DB;

/**
 * Class RequestCommand 生成 Request 文件
 * @package App\Commands
 */
class RequestCommand extends Command
{
    use CommandTrait;

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
     * @var string 生成目录
     */
    protected $basePath = 'app/Http/Requests';

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
        list($rules, $primary_key) = $this->rules($structure, $table);
        // 编辑
        $this->renderRequest('UpdateRequest.php', $this->getRules($rules));

        $id_rules = array_pull($rules, $primary_key);
        // 删除和新增验证
        $this->renderRequest('DestroyRequest.php', "['{$primary_key}' => '{$id_rules}']");
        $this->renderRequest('StoreRequest.php', $this->getRules($rules));
    }

    /**
     * 获取 rules 字符串
     *
     * @param array $rules
     *
     * @return string
     */
    private function getRules($rules)
    {
        $str_rules = var_export($rules, true);
        return str_replace(['array (', ')'], ['[', ']'], $str_rules);
    }

    /**
     * 获取命名空间
     *
     * @param $path
     *
     * @return string
     */
    private function getNameSpace($path)
    {
        if ($path && !starts_with($path, '/')) {
            $namespace = str_replace('/', '\\', $path);
        } else {
            $namespace = '';
        }

        return $namespace ? '\\' . $namespace : '';
    }

    /**
     * 渲染Request
     *
     * @param string $class_file 类文件名称
     * @param string $rules      规则
     */
    private function renderRequest($class_file, $rules)
    {
        $namespace  = $this->getNameSpace($this->option('path'));
        $base_path  = $this->getPath('');
        $file_name  = $base_path . $class_file;
        $class_name = str_replace('.php', '', $class_file);
        $this->render($file_name, compact('namespace', 'rules', 'class_name'));
    }

    /**
     * 获取路由规则
     *
     * @param array  $array
     * @param string $table
     *
     * @return array
     */
    private function rules($array, $table)
    {
        $rules       = [];
        $primary_key = null;
        foreach ($array as $row) {
            $field = array_get($row, 'Field');
            if (in_array($field, ['created_at', 'updated_at'])) {
                continue;
            }

            $tmp_rules = [];
            // 不能为空
            if (array_get($row, 'Null') == 'NO') {
                $tmp_rules[] = 'required';
            }

            // 类型处理
            $type = array_get($row, 'Type');
            if ($this->isInt($type)) {
                $tmp_rules[] = 'integer';
            } elseif ($string = $this->isString($type)) {
                $tmp_rules[] = 'string';
                if ($min = array_get($string, 'min', 2)) {
                    $tmp_rules[] = 'min:' . $min;
                }

                if ($max = array_get($string, 'max')) {
                    $tmp_rules[] = 'max:' . $max;
                }
            }

            // 主键
            if (array_get($row, 'Key') == 'PRI') {
                $tmp_rules[] = 'min:1|exists:' . $table;
                $primary_key = $field;
            }

            $rules[$field] = implode('|', $tmp_rules);
        }

        return [$rules, $primary_key];
    }

    /**
     * 获取渲染模板
     *
     * @return mixed|string
     */
    public function getRenderHtml()
    {
        return <<<html
<?php

namespace App\Http\Requests{namespace};

use App\Http\Requests\Request;

class {class_name} extends Request
{
    public function rules()
    {
        return {rules};
    }
}
html;

    }
}