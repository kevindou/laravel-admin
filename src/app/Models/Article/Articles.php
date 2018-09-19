<?php

namespace App\Models\Article;

use App\Models\Model;

class Articles extends Model
{
    protected $table      = 'articles';
    protected $primaryKey = 'id';
    public    $columns    = [
		'id',
		'type_id',
		'author',
		'title',
		'keywords',
		'excerpt',
		'content',
		'thumb_image',
		'view_num',
        'recommend',
		'format',
		'status',
        'sort',
		'created_at',
		'updated_at',
	];

    /**
     * 文章分类信息
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function typeInfo()
    {
        return $this->hasOne(ArticleTypes::class, 'type_id', 'type_id');
    }
}