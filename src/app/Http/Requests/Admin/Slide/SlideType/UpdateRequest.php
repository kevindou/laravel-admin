<?php

namespace App\Http\Requests\Admin\Slide\SlideType;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function rules()
    {
        return [
            'type_id'     => 'required|integer|min:1|exists:slide_types',
            'name'        => 'required|string|min:2|max:100',
            'description' => 'required|string|min:2|max:191',
            'status'      => 'required|integer',
        ];
    }
}