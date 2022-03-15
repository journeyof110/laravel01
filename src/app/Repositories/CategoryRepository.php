<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Repository;

class CategoryRepository extends Repository
{
    /**
     * すべてのカテゴリーを取得
     *
     * @return object
     */
    public function all(): object
    {
        return Category::all();
    }
}
