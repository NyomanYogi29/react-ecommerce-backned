<?php

namespace App\Repository;

use App\Contracts\CategoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryInterface
{
    public function getIdByName(string $name): string
    {
        return Category::where('name', $name)->firstOrFail()->id;
    }
}
