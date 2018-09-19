<?php

namespace App\Repositories\Article;

use App\Models\Article\Articles;
use App\Repositories\Repository;

class ArticlesRepository extends Repository
{
    public function __construct(Articles $model)
    {
        parent::__construct($model);
    }
}