<?php

namespace App\Models\Nav;

use App\Models\Model;

/**
 * App\Models\Nav\NavType
 *
 * @property int            $id          主键ID
 * @property string         $name        导航分类名称
 * @property string         $description 导航分类说明
 * @property \Carbon\Carbon $created_at  创建时间
 * @property \Carbon\Carbon $updated_at  修改时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\NavType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\NavType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\NavType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\NavType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Nav\NavType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NavType extends Model
{
    protected $table      = 'nav_types';
    protected $primaryKey = 'type_id';
    public    $columns    = [
        'type_id',
        'name',
        'status',
        'description',
        'created_at',
        'updated_at',
    ];

    /**
     * 导航信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function navInfo()
    {
        return $this->hasMany(Nav::class, 'type_id', 'type_id')
            ->where('status', 1)
            ->orderBy('sort')
            ->orderBy('id');
    }
}