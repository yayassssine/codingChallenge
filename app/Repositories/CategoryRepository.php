<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{
    public function getAll(): Collection
    {
        return Category::all();
    }

    public function findById(int $categoryId): ?Category
    {
        return Category::find($categoryId);
    }

    public function exists(int $categoryId): bool
    {
        return Category::where('id', $categoryId)->exists();
    }
}
