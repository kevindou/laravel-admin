<?php

namespace App\Http\Requests\Admin\Article\Articles;

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
            'type_id'     => 'required|integer|min:1|exists:article_types',
            'author'      => 'required|string|min:2|max:20',
            'title'       => 'required|string|min:2|max:100',
            'keywords'    => 'required|string|min:2|max:150',
            'excerpt'     => 'required|string|max:500',
            'content'     => 'string|min:2',
            'thumb_image' => 'sometimes|string|max:255',
            'view_num'    => 'sometimes|required|integer',
            'format'      => 'required|integer',
            'status'      => 'required|integer',
        ];
    }
}