<?php

namespace App\Http\Requests\Admin\Admins;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'name'     => 'required|string|min:2|max:50|unique:admins',
            'email'    => 'required|string|min:2|max:100|email|unique:admins',
            'password' => 'required|string|min:6',
            'status'   => 'required|integer'
        ];
    }
}