<?php

namespace App\Http\Requests\Admin\Menus;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'name'   => 'required|string|between:2,50',
            'icon'   => 'present|string|between:2,255',
            'url'    => 'present|string|between:1,255',
            'status' => 'required|integer'
        ];
    }
}