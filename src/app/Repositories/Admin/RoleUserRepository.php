<?php

namespace App\Repositories\Admin;

use App\Models\Admin\RoleUser;
use App\Repositories\Repository;

class RoleUserRepository extends Repository
{
    public function __construct(RoleUser $model)
    {
        parent::__construct($model);
    }

    /**
     * 添加用户角色信息
     *
     * @param integer      $user_id  用户ID
     * @param array|string $role_ids 角色ID信息
     *
     * @return array
     */
    public function createUserRoles($user_id, $role_ids)
    {
        $role_ids = is_array($role_ids) ? $role_ids : explode(',', $role_ids);
        if (empty($role_ids)) {
            return $this->error();
        }

        foreach ($role_ids as $role_id) {
            if (empty($role_id)) {
                continue;
            }

            $this->create(['user_id' => $user_id, 'role_id' => $role_id]);
        }

        return $this->success();
    }
}