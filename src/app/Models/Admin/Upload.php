<?php

namespace App\Models\Admin;

/**
 * Class Uploads 上传文件表
 * @package App\Models
 */
class Upload extends Model
{
    /**
     * @var string 定义表名字
     */
    protected $table = 'uploads';

    /**
     * 表的字段
     *
     * @var array
     */
    public $columns = [
        'id',
        'title',
        'name',
        'url',
        'path',
        'extension',
        'public',
        'created_at',
        'updated_at',
    ];
}
