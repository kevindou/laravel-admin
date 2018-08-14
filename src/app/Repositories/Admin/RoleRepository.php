<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Role;
use App\Repositories\Repository;
use Illuminate\Support\Facades\DB;

class RoleRepository extends Repository
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    /**
     * 添加角色
     *
     * @param $condition
     *
     * @return array
     * @throws \Throwable
     */
    public function deleteRole($condition)
    {
        DB::beginTransaction();
        try {
            // 删除自己
            list($ok, $msg) = $this->delete($condition);
            throw_if(!$ok, new \Exception($msg));

            // 删除角色对应权限
            app(PermissionRepository::class)->delete(['role_id' => array_get($condition, 'id')]);
            DB::commit();
            return $this->success($condition);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 修改权限
     *
     * @param integer $role_id     角色ID
     * @param array   $data        修改数据
     * @param array   $permissions 权限信息
     *
     * @return array
     * @throws \Throwable
     */
    public function updatePermissions($role_id, $data, $permissions)
    {
        DB::beginTransaction();
        try {
            // 删除自己
            list($ok, $msg) = $this->update($role_id, $data);
            throw_if(!$ok, new \Exception($msg));
            $this->getModel()->where('id', $role_id)->first()->perms()->sync($permissions);
            DB::commit();
            return $this->success($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}