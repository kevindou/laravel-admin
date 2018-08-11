<?php

namespace App\Models\Admin;

use App\Models\Model;

/**
 * Class Menu
 *
 * @package App\Models
 * @property integer $id
 * @property integer $parent
 * @property integer $status
 */
class Menu extends Model
{
    /**
     * 开启状态
     */
    const STATUS_ENABLES = 10;

    /**
     * 停用状态
     */
    const STATUS_DISABLES = 0;

    /**
     * 删除状态
     */
    const STATUS_DELETE = -1;

    /**
     * @var string 定义表名字
     */
    protected $table = 'menus';

    /**
     * @var array 批量赋值的黑名单
     */
    protected $guarded = ['id'];

    public $columns = [
        'id',
        'name',
        'url',
        'icon',
        'parent',
        'status',
        'sort',
        'created_at',
        'updated_at',
    ];

    /**
     * 获取状态信息
     *
     * @param null $intStatus 状态值
     *
     * @return array|mixed
     */
    public static function getStatus($intStatus = null)
    {
        $mixReturn = [
            self::STATUS_ENABLES  => '启用',
            self::STATUS_DISABLES => '停用',
            self::STATUS_DELETE   => '删除',
        ];

        if ($intStatus !== null) $mixReturn = isset($mixReturn[$intStatus]) ? $mixReturn[$intStatus] : null;

        return $mixReturn;
    }

    /**
     * 父级信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parentInfo()
    {
        return $this->hasOne(self::class, 'id', 'parent');
    }
}
