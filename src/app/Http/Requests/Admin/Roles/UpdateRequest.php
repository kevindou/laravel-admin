<?php

namespace App\Http\Requests\Admin\Roles;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function rules()
    {
        return [
            'id'           => 'required|integer|min:1|exists:roles',
            'name'         => 'required|string|between:2,190',
            'description'  => 'required|string|between:2,190',
            'display_name' => 'required|string|between:2,190',
        ];
    }
}