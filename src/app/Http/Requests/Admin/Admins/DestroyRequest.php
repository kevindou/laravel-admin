<?php

namespace App\Http\Requests\Admin\Admins;

use App\Http\Requests\Request;

class DestroyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->input('id') != config('admin.super_admin_id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|min:1|exists:admins,id',
        ];
    }
}