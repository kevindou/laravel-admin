<?php

namespace App\Models\Slide;

use App\Models\Model;

class SlideType extends Model
{
    protected $table      = 'slide_types';
    protected $primaryKey = 'type_id';
    public    $columns    = [
		'type_id',
		'name',
		'description',
		'status',
		'created_at',
		'updated_at',
	];

    /**
     * 幻灯片信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function slides()
    {
        return $this->hasMany(Slide::class, 'type_id', 'type_id')
            ->where('status', 1);
    }
}