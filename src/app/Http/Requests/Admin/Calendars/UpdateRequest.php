<?php

namespace App\Http\Requests\Admin\Calendars;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function rules()
    {
        return [
            'id'          => 'required|integer|min:1|exists:calendars',
            'title'       => 'required|string|between:2,255',
            'desc'        => 'required|string|between:2,255',
            'start'       => 'required|string|date',
            'end'         => 'required|string|date',
            'status'      => 'required|integer',
            'time_status' => 'required|integer'
        ];
    }
}