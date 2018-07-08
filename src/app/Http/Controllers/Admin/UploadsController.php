<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Uploads\DestroyRequest;
use App\Http\Requests\Admin\Uploads\UpdateRequest;
use App\Http\Requests\Admin\Uploads\UploadRequest;
use App\Repositories\Admin\UploadRepository;
use Illuminate\Support\Facades\Storage;

/**
 * Class UploadsController
 * 文件上传处理
 *
 * @package App\Http\Controllers\Admin
 */
class UploadsController extends Controller
{
    public function __construct(UploadRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * 首页显示
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::uploads.index');
    }

    /**
     * 获取列表信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        return $this->success($this->repository->findAll());
    }

    /**
     * 文件上传 Upload files via DropZone.js
     *
     * @param UploadRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(UploadRequest $request)
    {
        $file = $request->file('file');
        if (!$file->isValid()) {
            return $this->error(1001);
        }

        // 上传文件
        if (!$url = $file->store(date('Ymd'))) {
            return $this->error(1004);
        }

        // 新增数据
        return $this->sendJson($this->repository->create([
            'name'      => $file->getClientOriginalName(),
            'url'       => Storage::url($url),
            'path'      => $url,
            'title'     => '',
            'extension' => $file->getClientOriginalExtension(),
            'public'    => 1
        ]));
    }

    /**
     * 删除图片信息
     *
     * @param DestroyRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DestroyRequest $request)
    {
        if ($one = $this->repository->findOne($request->input('id'))) {
            $data = $this->repository->delete(['id' => $one['id']]);
            if ($data[0] && $path = array_get($one, 'path')) {
                Storage::delete($path);
            }

            return $this->sendJson($data);
        }

        return $this->error(1003);
    }

    /**
     * 修改图片上传信息
     *
     * @param UpdateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $id = $request->input('id');
        list($ok, $msg) = $this->repository->update($id, $request->all());
        if ($ok) {
            return $this->success($this->repository->findOne($id));
        }

        return $this->error(1001, $msg);
    }

    /**
     * 文件下载
     *
     * @param DestroyRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(DestroyRequest $request)
    {
        $url = (string)$this->repository->findColumn($request->input('id'), 'url');
        return response()->download('.' . ltrim($url, '.'));
    }
}
