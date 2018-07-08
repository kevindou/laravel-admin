<?php

namespace App\Http\Requests\Admin\Calendars;

use App\Http\Requests\Request;

class DestroyRequest extends Request
{
    public function rules()
    {
        return [
            'id' => 'required|integer|min:1|exists:calendars',
        ];
    }
}