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
        list($rules, $primary_key) = $this->rule($structure, $table);
        $str_rules   = var_export($rules, true);
        $str_rules   = str_replace(['array (', ')'], ['[', ']'], $str_rules);
        $update_name = $this->getPath('UpdateRequest.php');
        if ($this->isWrite($update_name)) {
            $this->renderRequest($update_name, $str_rules);
        }

        $id_rules     = array_pull($rules, $primary_key);
        $destory_name = $this->getPath('DestroyRequest.php');
        if ($this->isWrite($destory_name)) {
            $this->renderRequest($destory_name, "['{$primary_key}' => '{$id_rules}']");
        }

        $str_rules  = var_export($rules, true);
        $str_rules  = str_replace(['array (', ')'], ['[', ']'], $str_rules);
        $store_name = $this->getPath('StoreRequest.php');
        if ($this->isWrite($store_name)) {
            $this->renderRequest($store_name, $str_rules);
        }
    }

    /**
     * 是否写文件
     *
     * @param $file_name
     *
     * @return bool
     */
    private function isWrite($file_name)
    {
        if (file_exists($file_name) && !$this->confirm('文件[ ' . $file_name . ' ]已经存在是否覆盖？')) {
            return false;
        }

        return true;
    }

    private function renderRequest($file_name, $rules)
    {
        // 类名
        $name = array_get(explode('.', basename($file_name)), 0);
        // 命名空间
        $arr_path = explode('/', dirname($file_name));
        if (in_array('Requests', $arr_path)) {
            $namespace = [];
            do {
                $dir_name = array_pop($arr_path);
                if ($dir_name != 'Requests') {
                    array_unshift($namespace, $dir_name);
                }
            } while ($dir_name != 'Requests' && !empty($arr_path));
            $namespace = implode('\\', $namespace);
        } else {
            $namespace = '';
        }

        $namespace = $namespace ? '\\' . $namespace : '';
        $html      = <<<html
<?php

namespace App\Http\Requests{$namespace};

use App\Http\Requests\Request;

class {$name} extends Request
{
    public function rules()
    {
        return {$rules};
    }
}
html;

        $this->info('文件[ ' . $file_name . ' ]写入成功');
        return file_put_contents($file_name, $html);
    }

    private function rule($array, $table)
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
                $tmp_rules[] = $string;
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

    private function isInt($type)
    {
        return $this->isStartWith(['tinyint', 'smallint', 'mediumint', 'int', 'bigint'], $type);
    }

    private function isString($type)
    {
        if ($this->isStartWith(['char', 'varchar', 'text'], $type)) {
            preg_match('/\d+/', $type, $array);
            $return = 'string|min:2';
            if ($array) {
                $return .= '|max:' . array_get($array, 0);
            }

            return $return;
        }

        return false;
    }

    private function isStartWith($array, $type)
    {
        $is_start_with = false;
        foreach ($array as $start) {
            if (starts_with($type, $start)) {
                $is_start_with = true;
                break;
            }
        }

        return $is_start_with;
    }
}