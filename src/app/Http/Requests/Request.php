<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/7 0007
 * Time: 下午 10:32
 */

namespace App\Http\Requests;

use App\Traits\JsonTrait;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    use JsonTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}