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

if (!function_exists('is_empty')) {
    /**
     * 判断是否为空 0 值不算
     *
     * @param mixed $value 判断的值
     *
     * @return boolean 是空返回 true
     */
    function is_empty($value)
    {
        return $value === '' || $value === [] || $value === null || is_string($value) && trim($value) === '';
    }
}

if (!function_exists('filter_array')) {
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
}

if (!function_exists('array_multi_sort')) {
    /**
     * 多维数组排序
     *
     * @param array  $array 排序的数据
     * @param string $key   按照那个数组排序
     * @param int    $desc  排序的方式
     */
    function array_multi_sort(&$array, $key, $desc)
    {
        array_multisort(array_column($array, $key), $desc, $array);
    }
}

if (!function_exists('html_attribute')) {
    /**
     * 处理 html 属性
     *
     * @param array $params 属性信息
     *                      ['id' => 'user', 'class' => 'item li']
     *
     * @return string
     *               'id="user" class="item li"'
     */
    function html_attribute($params)
    {
        if (empty($params) || !is_array($params)) {
            return '';
        }

        $html = '';
        foreach ($params as $attr => $value) {
            $html .= " $attr=\"{$value}\" ";
        }

        return $html;
    }
}

if (!function_exists('render_menu')) {
    /**
     * 渲染后台导航
     *
     * @param array $menu 导航信息
     *                    ['id' => 1, 'url' => '/admin/menu', 'children' => [], 'icon' => 'fa-home']
     * @param array $params 渲染配置
     *                      ['ul' => 'ul attributes 信息']
     *
     * @return string
     */
    function render_menu($menu, $params = [])
    {
        if (empty($menu) || !is_array($menu)) {
            return '';
        }

        $html = '<ul ' . html_attribute(array_get($params, 'ul')) . '>';
        foreach ($menu as $item) {
            $children  = array_get($item, 'children');
            $url       = array_get($item, 'url');
            $href      = url($url);
            $i_html    = $children ? '<i class="fa fa-angle-left pull-right"></i>' : '';
            $tree_view = $children ? 'treeview' : '';
            $html      .= "<li class=\"{$tree_view}\" data-url=\"{$url}\" data-id=\"{$item['id']}\">";
            $html      .= "<a href=\"{$href}\">
                            <i class=\"fa {$item['icon']}\"></i>
                            <span>{$item['name']}</span>
                            {$i_html}
                       </a>";
            if ($children) {
                $html .= render_menu($children, ['ul' => ['class' => 'treeview-menu']]);
            }
            $html .= '<li>';
        }

        return $html . '</ul>';
    }
}