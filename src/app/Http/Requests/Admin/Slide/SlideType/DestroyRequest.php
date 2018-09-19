<?php

namespace App\Http\Requests\Admin\Slide\SlideType;

use App\Http\Requests\Request;

class DestroyRequest extends Request
{
    public function rules()
    {
        return ['type_id' => 'required|integer|min:1|exists:slide_types'];
    }
}