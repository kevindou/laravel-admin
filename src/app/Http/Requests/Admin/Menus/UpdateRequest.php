<?php

namespace App\Http\Requests\Admin\Menus;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function rules()
    {
        return [
            'id' => 'required|integer|min:1|exists:menus',
        ];
    }
}