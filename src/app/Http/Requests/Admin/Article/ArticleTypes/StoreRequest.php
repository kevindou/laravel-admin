<?php

namespace App\Http\Requests\Admin\Article\ArticleTypes;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'        => 'required|string|min:2|max:100',
            'description' => 'string|max:191',
            'sort'        => 'required|integer',
            'status'      => 'required|integer',
        ];
    }
}