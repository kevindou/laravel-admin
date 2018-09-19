<?php

namespace App\Http\Requests\Admin\Article\ArticleTypes;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type_id'     => 'required|integer|min:1|exists:article_types',
            'name'        => 'required|string|min:2|max:100',
            'description' => 'string|max:191',
            'sort'        => 'required|integer',
            'status'      => 'required|integer',
        ];
    }
}