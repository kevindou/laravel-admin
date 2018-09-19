<?php

namespace App\Http\Requests\Admin\Nav\NavType;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function rules()
    {
        return [
            'type_id'     => 'required|integer|min:1|exists:nav_types',
            'name'        => 'required|string|min:2|max:100',
            'description' => 'required|string|min:2|max:191',
            'status'      => 'required|integer|in:1,2',
        ];
    }
}