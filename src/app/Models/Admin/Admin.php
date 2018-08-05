<?php

namespace App\Models\Admin;

use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User;

/**
 * Class Admin
 * @package App\Models
 */
class Admin extends User
{
    use EntrustUserTrait;

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public $columns = [
        'id',
        'name',
        'email',
        'avatar',
        'password',
        'remember_token',
        'status',
        'created_at',
        'updated_at',
    ];

    public $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'remember_token',
        'status',
        'created_at',
        'updated_at',
    ];

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
    protected $table = 'admins';

    /**
     * @var array 批量赋值的黑名单
     */
    protected $guarded = ['id'];

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

        if ($intStatus !== null) {
            return array_get($mixReturn, $intStatus, null);
        }

        return $mixReturn;
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param $value
     */
    public function scopeUser($query, $value)
    {
        $query->whereIn('id', (array)$value);
    }

    public function roleInfo()
    {
        return $this->hasOne(RoleUser::class, 'user_id', 'id');
    }
}
