<?php

namespace App\Http\Requests\Admin\Article\Articles;

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
            'id'          => 'required|integer|min:1|exists:articles',
            'type_id'     => 'required|integer|min:1|exists:article_types',
            'author'      => 'required|string|min:2|max:20',
            'title'       => 'required|string|min:2|max:100',
            'keywords'    => 'required|string|min:2|max:150',
            'excerpt'     => 'required|string|max:500',
            'content'     => 'string|min:2',
            'thumb_image' => 'sometimes|string|max:255',
            'view_num'    => 'sometimes|required|integer',
            'format'      => 'sometimes|required|integer|in:1,2',
            'status'      => 'required|integer',
        ];
    }
}