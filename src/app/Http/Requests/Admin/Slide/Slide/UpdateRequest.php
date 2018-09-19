<?php

namespace App\Http\Requests\Admin\Slide\Slide;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function rules()
    {
        return [
            'id'          => 'required|integer|min:1|exists:slides',
            'type_id'     => 'required|integer',
            'title'       => 'required|string|min:2|max:50',
            'description' => 'required|string|min:2|max:191',
            'content'     => 'string|min:2',
            'image'       => 'required|string|min:2|max:191',
            'url'         => 'required|string|min:2|max:191',
            'target'      => 'required|string|min:2|max:10',
            'status'      => 'required|integer',
            'sort'        => 'required|integer',
        ];
    }
}