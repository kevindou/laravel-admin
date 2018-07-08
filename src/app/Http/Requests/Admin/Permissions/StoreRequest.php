<?php

namespace App\Http\Requests\Admin\Permissions;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'name'         => 'required|string|between:2,190|unique:permissions',
            'description'  => 'required|string|between:2,190',
            'display_name' => 'required|string|between:2,190',
        ];
    }
}