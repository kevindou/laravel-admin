<?php

namespace App\Http\Requests\Admin\Article\Articles;

use App\Http\Requests\Request;

class DestroyRequest extends Request
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return ['id' => 'required|integer|min:1|exists:articles'];
    }
}