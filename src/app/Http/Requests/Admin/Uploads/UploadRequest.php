<?php

namespace App\Http\Requests\Admin\Uploads;

use App\Http\Requests\Request;

class UploadRequest extends Request
{
    public function rules()
    {
        return [
            'file' => 'file|max:100000'
        ];
    }
}