<?php

namespace App\Http\Requests\Admin\Uploads;

use App\Http\Requests\Request;

class DestroyRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|min:1|exists:uploads',
        ];
    }
}
