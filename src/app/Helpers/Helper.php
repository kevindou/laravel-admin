<?php

namespace App\Helpers;

use Closure;

/**
 * Class Helper 助手类
 * @package App\Helpers
 */
class Helper
{
    /**
     * @var int json默认配置 JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
     */
    const JSON_OPTIONS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    /**
     * json encode 处理中文处理
     *
     * @param  mixed $mixed  转义数据
     * @param  int $options 转义配置
     *
     * @return string 返回json字符串
     */
    public static function encode($mixed, $options = self::JSON_OPTIONS)
    {
        return json_encode($mixed, $options);
    }

    /**
     * json decode 解析json字符串
     *
     * @param  string $json json字符串
     * @param  bool $assoc  默认转成数组
     *
     * @return bool|array   数组或者false
     */
    public static function decode($json, $assoc = true)
    {
        return json_decode($json, $assoc);
    }

    /**
     * 获取IP地址
     *
     * @return string 返回字符串
     */
    public static function getIpAddress()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $strIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $strIpAddress = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $strIpAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $strIpAddress = getenv('HTTP_X_FORWARDED_FOR');
            } else if (getenv('HTTP_CLIENT_IP')) {
                $strIpAddress = getenv('HTTP_CLIENT_IP');
            } else {
                $strIpAddress = getenv('REMOTE_ADDR') ? getenv('REMOTE_ADDR') : '';
            }
        }

        return $strIpAddress;
    }

    /**
     * 处理查询参数信息
     *
     * @param \Illuminate\Database\Query\Builder $query 查询对象
     * @param array $params 查询参数
     * @param array $where 查询的配置信息
     */
    public static function handleWhere($query, $params, $where)
    {
        // 第一步：验证参数不能为空
        if (empty($params) || empty($where)) {
            return;
        }

        // 处理查询
        foreach ($where as $column => $value) {
            if (!isset($params[$column]) || $params[$column] === '') {
                continue;
            }

            if ($value instanceof Closure) {
                $value($query, $column, $params[$column]);
                continue;
            }

            if (is_array($value)) {
                $column = $value['column'] ?? $column;
                $operator = $value['operator'] ?? '=';
                if (
                    isset($value['callback']) &&
                    (function_exists($value['callback']) || $value['callback'] instanceof Closure)
                ) {
                    $params[$column] = $value['callback']($params[$column]);
                }
            } else {
                $operator = (string) $value;
            }

            static::handleMethod($query, $column, $operator, $params[$column]);
        }
    }

    /**
     * 处理查询的方式
     *
     * @param \Illuminate\Database\Query\Builder $query 查询对象
     * @param string $column 查询字段
     * @param string $operator 连接操作符
     * @param mixed $value 查询的值
     */
    public static function handleMethod($query, $column, $operator, $value)
    {
        switch (strtolower($operator)) {
            case 'in':
            case 'not in':
            case 'between':
            case 'not between':
                $methods = explode(' ', $operator);
                foreach ($methods as &$val) {
                    $val = ucfirst($val);
                }

                unset($val);
                $strMethod = 'where' . implode('', $methods);
                $query->{$strMethod}($column, $value);
                break;
            case 'like':
            case 'not like':
                $query->where($column, $operator, '%' . (string) $value . '%');
                break;
            default:
                $query->where($column, $operator, $value);
        }
    }

}
