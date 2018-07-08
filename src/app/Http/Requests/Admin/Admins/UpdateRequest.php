<?php

namespace App\Http\Requests\Admin\Admins;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function rules()
    {
        $rules = [
            'id'     => 'required|integer|min:1|exists:admins,id',
            'name'   => 'required|string|min:2|max:50',
            'email'  => 'required|string|min:2|max:100|email',
            'status' => 'required|integer'
        ];

        if (request()->input('password')) {
            $rules['password'] = 'required|string|min:6';
        }

        return $rules;
    }
}