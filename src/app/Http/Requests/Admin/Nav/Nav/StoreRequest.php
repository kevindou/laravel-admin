<?php

namespace App\Http\Requests\Admin\Nav\Nav;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'parent_id' => 'required|integer',
            'type_id'   => 'required|integer',
            'name'      => 'required|string|min:2|max:100',
            'url'       => 'required|string|min:2|max:100',
            'icon'      => 'string|max:20',
            'target'    => 'required|string|min:2|max:10',
            'status'    => 'required|integer|in:1,2',
            'sort'      => 'required|integer',
        ];
    }
}