<?php

namespace App\Http\Requests\Admin\RoleUsers;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'user_id' => 'required|integer|min:1|exists:admins,id',
            'role_id' => 'required|integer|min:1|exists:'.config('entrust.roles_table').',id',
        ];
    }
}