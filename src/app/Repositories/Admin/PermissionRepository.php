<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Permission;
use App\Repositories\Repository;
use Illuminate\Support\Facades\DB;

class PermissionRepository extends Repository
{
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    /**
     * @param $data
     *
     * @return array
     * @throws \Throwable
     */
    public function createPermission($data)
    {
        DB::beginTransaction();
        try {
            // 添加
            list($ok, $msg, $permission) = $this->create($data);
            throw_if(!$ok, new \Exception($msg));

            // 添加超级管理员角色
            list($ok, $msg) = app(PermissionRoleRepository::class)->create([
                'permission_id' => array_get($permission, 'id'),
                'role_id'       => config('admin.super_role_id')
            ]);
            throw_if(!$ok, new \Exception($msg));
            DB::commit();
            return $this->success($permission);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 删除权限
     *
     * @param $condition
     *
     * @return array
     * @throws \Throwable
     */
    public function deletePermission($condition)
    {
        DB::beginTransaction();
        try {
            // 删除自己
            list($ok, $msg) = $this->delete($condition);
            throw_if(!$ok, new \Exception($msg));

            // 删除权限对应角色
            app(PermissionRoleRepository::class)->delete(['permission_id' => array_get($condition, 'id')]);
            DB::commit();
            return $this->success($condition);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}