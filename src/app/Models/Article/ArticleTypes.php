<?php

namespace App\Models\Article;

use App\Models\Model;

class ArticleTypes extends Model
{
    protected $table      = 'article_types';
    protected $primaryKey = 'type_id';
    public    $columns    = [
		'type_id',
		'name',
		'description',
		'sort',
		'status',
		'created_at',
		'updated_at',
	];
}