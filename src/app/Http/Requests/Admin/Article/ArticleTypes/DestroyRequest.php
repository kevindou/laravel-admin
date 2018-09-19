<?php

namespace App\Http\Requests\Admin\Article\ArticleTypes;

use App\Http\Requests\Request;

class DestroyRequest extends Request
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return ['type_id' => 'required|integer|min:1|exists:article_types'];
    }
}