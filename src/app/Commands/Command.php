<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/12 0012
 * Time: 上午 9:56
 */

namespace App\Commands;

abstract class Command extends \Illuminate\Console\Command
{
    protected $basePath = '';

    /**
     * 获取目录
     *
     * @param  string $file_name 文件名称
     *
     * @param bool    $use_path  是否使用 option path
     *
     * @return string
     */
    protected function getPath($file_name, $use_path = true)
    {
        // 处理路径
        $path = $use_path ? $this->option('path') : '';
        if (is_empty($path) || !starts_with($path, '/')) {
            $path = base_path(rtrim($this->basePath, '/') . '/' . $path);
        }

        return trim($path, '/') . '/' . ltrim($file_name, '/');
    }

    /**
     * 是否写文件
     *
     * @param $file_name
     *
     * @return bool
     */
    protected function isWrite($file_name)
    {
        return !(file_exists($file_name) && !$this->confirm('文件[ ' . $file_name . ' ]已经存在是否覆盖？'));
    }

    /**
     * 渲染生成文件
     *
     * @param $file_name
     * @param $params
     */
    protected function render($file_name, $params)
    {
        $dir_name = dirname($file_name);
        if (!function_exists($dir_name)) {
            @mkdir($dir_name, 0755, true);
        }

        if ($this->isWrite($file_name)) {
            $html   = $this->getRenderHtml();
            $search = array_map(function ($value) {
                return '{' . $value . '}';
            }, array_keys($params));

            file_put_contents($file_name, str_replace($search, array_values($params), $html));
            $this->info('文件[ ' . $file_name . ' ]生成成功');
        } else {
            $this->info('文件存在，不处理');
        }
    }

    /**
     * @return mixed 获取渲染的模板html
     */
    abstract function getRenderHtml();
}