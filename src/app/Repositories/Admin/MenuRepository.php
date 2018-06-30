<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Menu;
use App\Repositories\Repository;

class MenuRepository extends Repository
{
    public function __construct(Menu $model)
    {
        $this->model = $model;
    }

    /**
     * 获取权限对应的导航栏目信息
     *
     * @return array
     */
    public function getPermissionMenus()
    {
        // 查询数据
        $arrReturn = [];
        if ($all = $this->findAll(
            ['status' => Menu::STATUS_ENABLES],
            ['id', 'name', 'url', 'icon', 'parent', 'sort']
        )) {
            foreach ($all as $value) {
                $id     = array_get($value, 'id');
                $parent = array_get($value, 'parent');
                if ($parent == 0) {
                    $arrReturn[$id] = array_merge($arrReturn[$id] ?? ['child' => []], $value);
                } else {
                    if (isset($arrReturn[$parent])) {
                        $arrReturn[$parent]['child'][] = $value;
                    } else {
                        $arrReturn[$parent] = ['child' => [$value]];
                    }
                }
            }
        }

        return $arrReturn;
    }
}