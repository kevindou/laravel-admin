<?php

namespace App\Http\Requests\Admin\Calendars;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'title'       => 'required|string|between:2,255',
            'desc'        => 'required|string|between:2,255',
            'start'       => 'required|string|date',
            'end'         => 'required|string|date',
            'status'      => 'required|integer',
            'time_status' => 'required|integer'
        ];
    }
}