<?php

namespace App\Http\Requests\Admin\Nav\NavType;

use App\Http\Requests\Request;

class DestroyRequest extends Request
{
    public function rules()
    {
        return ['type_id' => 'required|integer|min:1|exists:nav_types'];
    }
}