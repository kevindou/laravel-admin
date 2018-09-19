<?php

namespace App\Models\Slide;

use App\Models\Model;

class Slide extends Model
{
    protected $table      = 'slides';
    protected $primaryKey = 'id';
    public    $columns    = [
		'id',
		'type_id',
		'title',
		'description',
		'content',
		'image',
		'url',
		'target',
		'status',
		'sort',
		'created_at',
		'updated_at',
	];

    /**
     * 类型信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function typeInfo()
    {
        return $this->hasOne(SlideType::class, 'type_id', 'type_id');
    }
}