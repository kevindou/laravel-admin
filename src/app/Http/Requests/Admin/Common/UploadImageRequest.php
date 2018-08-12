<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/12 0012
 * Time: 下午 2:56
 */

namespace App\Http\Requests\Admin\Common;

use App\Http\Requests\Request;

class UploadImageRequest extends Request
{
    public function rules()
    {
        return [
            'vue_image' => 'required|image|max:2048'
        ];
    }
}