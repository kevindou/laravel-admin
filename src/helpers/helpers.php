<?php

if (!function_exists('encode')) {
    /**
     * json encode 处理中文处理
     *
     * @param  mixed $mixed   转义数据
     * @param  int   $options 转义配置
     *
     * @return string 返回json字符串
     */
    function encode($mixed, $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    {
        return json_encode($mixed, $options);
    }
}

if (!function_exists('decode')) {
    /**
     * json decode 解析json字符串
     *
     * @param  string $json  json字符串
     * @param  bool   $assoc 默认转成数组
     *
     * @return bool|array   数组或者false
     */
    function decode($json, $assoc = true)
    {
        return json_decode($json, $assoc);
    }
}

if (!function_exists('get_ip')) {
    /**
     * 获取IP地址
     *
     * @return string 返回字符串
     */
    function get_ip()
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
}

if (!function_exists('handle_where')) {
    /**
     * 处理查询参数信息
     *
     * @param \Illuminate\Database\Query\Builder $query  查询对象
     * @param array                              $params 查询参数
     * @param array                              $where  查询的配置信息
     */
    function handle_where($query, $params, $where)
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
                $column   = $value['column'] ?? $column;
                $operator = $value['operator'] ?? '=';
                if (
                    isset($value['callback']) &&
                    (function_exists($value['callback']) || $value['callback'] instanceof Closure)
                ) {
                    $params[$column] = $value['callback']($params[$column]);
                }
            } else {
                $operator = (string)$value;
            }

            handle_method($query, $column, $operator, $params[$column]);
        }
    }
}

if (!function_exists('handle_method')) {
    /**
     * 处理查询的方式
     *
     * @param \Illuminate\Database\Query\Builder $query    查询对象
     * @param string                             $column   查询字段
     * @param string                             $operator 连接操作符
     * @param mixed                              $value    查询的值
     */
    function handle_method($query, $column, $operator, $value)
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
                $query->where($column, $operator, '%' . (string)$value . '%');
                break;
            default:
                $query->where($column, $operator, $value);
        }
    }
}

if (!function_exists('admin_path')) {
    /**
     * 获取目录地址
     *
     * @param $path
     *
     * @return string
     */
    function admin_path($path)
    {
        return __DIR__ . '/../' . ltrim($path, '/');
    }
}

if (!function_exists('array_studly_case')) {
    /**
     * 将数组元素转为大驼峰法
     * 例如：get-user-info GetUserInfo
     *
     * @param $params
     */
    function array_studly_case(array &$params)
    {
        foreach ($params as &$value) {
            $value = studly_case($value);
        }

        unset($value);
    }
}

if (!function_exists('get_controller_action')) {
    /**
     * 获取控制器名称和请求方法名称
     *
     * @param array $params 请求的参数
     *
     * @return array
     */
    function get_controller_action($params)
    {
        switch (count($params)) {
            case 0:
                // 根路由走默认控制器
                $controller = config('admin.defaultController') ?: 'IndexController';
                $action     = config('admin.defaultAction') ?: 'index';
                break;
            case 1:
                // 一个参数认为是控制器
                $controller = $params[0] . 'Controller';
                $action     = 'index';
                break;
            default:
                // 2个以上，倒数第一个为action，倒数第二个为控制器，其他为命名空间
                $action     = lcfirst(array_pop($params));
                $controller = implode('\\', $params) . 'Controller';
                break;
        }

        return [$controller, $action];
    }
}

/**
 * 判断是否为空 0 值不算
 *
 * @param mixed $value 判断的值
 *
 * @return boolean 是空返回ture
 */
function is_empty($value)
{
    return $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';
}

/**
 * 过滤数组数据
 *
 * @param array|mixed $array 数组信息
 *
 * @return array
 */
function filter_array($array)
{
    if (!is_array($array)) {
        return $array;
    }

    foreach ($array as $key => $value) {
        if (is_empty($value)) {
            unset($array[$key]);
        }
    }

    return $array;
}