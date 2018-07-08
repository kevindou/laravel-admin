<?php

namespace App\Http\Requests\Admin\Roles;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'name'         => 'required|string|between:2,190|unique:roles',
            'description'  => 'required|string|between:2,190',
            'display_name' => 'required|string|between:2,190',
        ];
    }
}