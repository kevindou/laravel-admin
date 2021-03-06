<?php

namespace App\Repositories\Admin;

use App\Helpers\Tree;
use App\Models\Admin\Menu;
use App\Repositories\Repository;

class MenuRepository extends Repository
{
    /**
     * @var Tree
     */
    private $tree;

    public function __construct(Menu $model, Tree $tree)
    {
        parent::__construct($model);
        $this->tree = $tree;
    }

    /**
     * 获取权限对应的导航栏目信息
     *
     * @param integer $uid 用户ID
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

        return $this->tree->init([
            'parentIdName' => 'parent',
            'childrenName' => 'children',
            'array'        => $menus,
        ])->getTreeArray(0, 0, 1, [
            'sort_key' => 'sort',
            'sort_by'  => SORT_ASC
        ]);
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

    /**
     * 查询父类信息
     *
     * @param $parent_id
     *
     * @return array|bool
     */
    public function findParents($parent_id)
    {
        if (empty($parent_id) || $parent_id < 0) {
            return false;
        }

        // 没有查询到直接返回
        if (!$one = $this->findOne(['parent' => $parent_id], ['*', 'parentInfo' => ['*']])) {
            return false;
        }

        // 没有父类直接返回
        $array = [$one];
        if (!$parent = array_get($one, 'parent_info')) {
            return $array;
        }

        // 父类没有父类了，直接返回
        array_unshift($array, $parent);
        if (!$parent_id = array_get($parent, 'parent')) {
            return $array;
        }

        // 父类可能还有几级父类处理
        do {
            array_unshift($array, array_pop($parents));
        } while ($parents);

        return $array;
    }
}