<?php

namespace App\Traits;

/**
 * Class Json 数据返回
 *
 * @package App\Http\Controllers\Admin\Json
 */
trait JsonTrait
{
    /**
     * @var array json 数据
     */
    public $json = [
        'code'    => 1000,
        'message' => '',
        'data'    => '',
    ];

    /**
     * 处理返回数据
     *
     * @param mixed $data 相应处理数据
     * @param int $code 错误码 0 => success
     * @param string $message
     */
    public function handleJson($data, $code = 0, $message = '')
    {
        list($this->json['data'], $this->json['code'], $this->json['message']) = func_get_args();
    }

    /**
     * 返回 json 数据信息
     *
     * @param array $params 返回数据信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnJson($params = [])
    {
        // 相应数据
        if ($params) $this->json = array_merge($this->json, $params);

        // 处理错误信息
        if (empty($this->json['message'])) {
            $this->json['message'] = trans('error.' . $this->json['code']);
        }

        return response()->json($this->json, 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * 处理成功返回
     *
     * @param  mixed|array $data 返回数据信息
     * @param  string $message 提示信息，默认为空
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data, $message = '')
    {
        return $this->returnJson([
            'code'    => 0,
            'message' => $message,
            'data'    => $data
        ]);
    }

    /**
     * 处理错误信息返回
     *
     * @param  int $code 错误码
     * @param  string $message 提示信息，默认为空
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($code = 1000, $message = '')
    {
        return $this->returnJson([
            'code'    => $code,
            'message' => $message,
            'data'    => null,
        ]);
    }
}