<?php

namespace App\Repositories\Article;

use App\Models\Article\ArticleTypes;
use App\Repositories\Repository;

class ArticleTypesRepository extends Repository
{
    public function __construct(ArticleTypes $model)
    {
        parent::__construct($model);
    }
}