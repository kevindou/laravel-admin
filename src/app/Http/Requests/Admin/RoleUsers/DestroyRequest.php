<?php

namespace App\Http\Requests\Admin\RoleUsers;

use App\Http\Requests\Request;

class DestroyRequest extends Request
{
    public function authorize()
    {
        if (
            request()->input('user_id') == config('admin.super_admin_id') &&
            request()->input('role_id') == config('admin.super_role_id')
        ) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer|min:1|exists:admins,id',
            'role_id' => 'required|integer|min:1|exists:' . config('entrust.roles_table') . ',id',
        ];
    }
}