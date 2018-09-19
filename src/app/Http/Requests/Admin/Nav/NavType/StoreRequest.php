<?php

namespace App\Http\Requests\Admin\Nav\NavType;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'name'        => 'required|string|min:2|max:100',
            'description' => 'required|string|min:2|max:191',
            'status'      => 'required|integer',
        ];
    }
}