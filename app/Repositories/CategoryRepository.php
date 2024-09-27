<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getAll()
    {
        return Category::all();
    }
    public function getProductIdsByCategory($categoryId)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return collect();
        }

        return $category->products()->pluck('id');
    }

    public function exists($categoryId)
    {
        return Category::where('id', $categoryId)->exists();
    }
}
