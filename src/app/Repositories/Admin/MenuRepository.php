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
    public function getPermissionMenus($uid = null)
    {
        if ($uid == config('admin.super_admin_id')) {
            $menus = $this->findAll(['status' => Menu::STATUS_ENABLES]);
        } else {
            $menus = $this->findUserMenus($uid);
        }

        // 查询数据
        if (empty($menus)) {
            return [];
        }

        $arrReturn = [];
        foreach ($menus as $value) {
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

        unset($menus);
        return $arrReturn;
    }

    /**
     * 通过用户id 查询用户角色拥有的菜单信息
     *
     * @param integer $uid 用户ID
     *
     * @return array
     */
    public function findUserMenus($uid)
    {
        return $this->findAllBySql('SELECT
                            `menus`.*
                        FROM
                            `menus`
                        INNER JOIN (
                            SELECT
                                `role_menus`.`menu_id`
                            FROM
                                `role_menus`
                            INNER JOIN `role_user` ON (`role_user`.`role_id` = `role_menus`.`role_id`)
                            WHERE `role_user`.`user_id` = :user_id
                        ) AS `t` ON (`t`.`menu_id` = `menus`.`id`)
                        WHERE
                            `menus`.`status` = :status',
            [
                'status'  => Menu::STATUS_ENABLES,
                'user_id' => $uid
            ]
        );
    }

    /**
     * 获取jstree 需要的数据
     *
     * @param array $array    数据信息
     * @param array $arrHaves 需要选中的数据
     *
     * @return array
     */
    public function getJsMenus($array, $arrHaves)
    {
        if (empty($array) || !is_array($array)) {
            return [];
        }

        $arrReturn = [];
        foreach ($array as $value) {
            $array = [
                'text'  => $value['name'],
                'id'    => $value['id'],
                'data'  => $value['url'],
                'state' => [],
            ];

            $array['state']['selected'] = in_array($value['id'], $arrHaves);
            $array['icon']              = $value['parent'] == 0 || !empty($value['children']) ? 'menu-icon fa fa-list orange' : false;
            if (!empty($value['children'])) {
                $array['children'] = $this->getJsMenus($value['children'], $arrHaves);
            }

            $arrReturn[] = $array;
        }

        return $arrReturn;
    }
}