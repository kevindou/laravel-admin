<?php

namespace App\Http\Requests\Admin\Uploads;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'     => 'required|integer|min:1|exists:uploads',
            'title'  => 'required|max:255|min:2',
            'name'   => 'required|max:255|min:6',
            'public' => 'required|integer'
        ];
    }
}
