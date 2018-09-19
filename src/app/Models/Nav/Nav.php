<?php

namespace App\Models\Nav;

use App\Models\Model;

/**
 * Class Nav
 *
 * @property int                 $id
 * @property int                 $parent_id
 * @property int                 $type_id 导航分类
 * @property string              $name    导航名称
 * @property string              $url     导航地址
 * @property string              $icon    使用的图标
 * @property string              $target  打开方式
 * @property int                 $status  状态 1 启用 2 停用
 * @property int                 $sort    排序
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\Nav whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\Nav whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\Nav whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\Nav whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\Nav whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\Nav whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\Nav whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\Nav whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\Nav whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\Nav whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\Nav whereUrl($value)
 * @mixin \Eloquent
 */
class Nav extends Model
{
    protected $table      = 'navs';
    protected $primaryKey = 'id';
    public    $columns    = [
        'id',
        'parent_id',
        'type_id',
        'name',
        'url',
        'icon',
        'target',
        'status',
        'sort',
        'created_at',
        'updated_at',
    ];

    /**
     * 导航分类信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function typeInfo()
    {
        return $this->hasOne(NavType::class, 'type_id', 'type_id');
    }
}