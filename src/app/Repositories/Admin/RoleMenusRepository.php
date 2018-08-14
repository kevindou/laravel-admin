<?php

namespace App\Repositories\Admin;

use App\Models\Admin\RoleMenus;
use App\Repositories\Repository;
use Illuminate\Support\Facades\DB;

class RoleMenusRepository extends Repository
{
    public function __construct(RoleMenus $model)
    {
        parent::__construct($model);
    }

    /**
     * 修改角色的菜单信息
     *
     * @param integer      $role_id  角色ID
     * @param string|array $menu_ids 菜单ID信息
     *
     * @return array
     * @throws \Throwable
     */
    public function updateMenus($role_id, $menu_ids)
    {
        $menu_ids = is_array($menu_ids) ? $menu_ids : explode(',', $menu_ids);
        DB::beginTransaction();
        try {
            // 先删除之前的
            list($ok, $msg) = $this->delete(['role_id' => $role_id]);

            // 添加超级管理员角色
            foreach ($menu_ids as $menu_id) {
                if (empty($menu_id)) {
                    continue;
                }

                list($ok, $msg) = $this->create(['menu_id' => intval($menu_id), 'role_id' => $role_id]);
                throw_if(!$ok, new \Exception($msg));
            }

            DB::commit();
            return $this->success($menu_ids);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}