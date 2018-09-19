<?php

namespace App\Http\Requests\Admin\Nav\Nav;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function rules()
    {
        return [
            'id'        => 'required|integer|min:1|exists:navs',
            'parent_id' => 'required|integer',
            'type_id'   => 'required|integer',
            'name'      => 'required|string|min:2|max:100',
            'url'       => 'required|string|min:1|max:100',
            'icon'      => 'string|max:20',
            'target'    => 'required|string|min:2|max:10',
            'status'    => 'required|integer|in:1,2',
            'sort'      => 'required|integer',
        ];
    }
}