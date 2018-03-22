<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreUpload;
use App\Models\Upload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\Traits\Json;

/**
 * Class UploadsController
 * 文件上传处理
 *
 * @package App\Http\Controllers\Admin
 */
class UploadsController extends Controller
{
    use Json;

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.uploads.index');
    }

    /**
     * 获取列表信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        return $this->success(Upload::all());
    }

    /**
     * 文件上传 Upload files via DropZone.js
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        if (!$request->isMethod('post')) {
            return $this->error();
        }

        $file = $request->file('file');
        if (!$file->isValid()) {
            return $this->error(1001);
        }

        // 上传文件
        $url = $file->store(date('Ymd'));
        if (!$url) {
            return $this->error(1004);
        }

        // 新增数据
        if ($upload = Upload::create([
            'name' => $file->getClientOriginalName(),
            'url' => Storage::url($url),
            'path' => $url,
            'title' => '',
            'extension' => $file->getClientOriginalExtension(),
            'public' => 1
        ])) {
            return $this->success($upload);
        } else {
            return $this->error(1005);
        }
    }

    /**
     * 删除图片信息
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $upload = Upload::findOrFail($request->input('id'));
        if ($upload->delete()) {
            Storage::delete($upload->path);
            return $this->success($upload);
        } else {
            return $this->error(1003);
        }
    }

    /**
     * 修改图片上传信息
     *
     * @param StoreUpload $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreUpload $request)
    {
        $upload = Upload::findOrFail($request->input('id'));
        $upload->fill($request->input());
        if ($upload->save()) {
            return $this->success($upload);
        } else {
            return $this->error(1003);
        }
    }

    /**
     * 文件下载
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Request $request)
    {
        return response()->download('.' . trim($request->input('file'), '.'));
    }
}
