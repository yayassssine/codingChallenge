<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use YouCanShop\QueryOption\QueryOption;

class ProductRepository
{
    public function filterByIds(array $productIds, QueryOption $queryOption): LengthAwarePaginator
    {
        return Product::whereIn('id', $productIds)
            ->paginate(
                $queryOption->getLimit(),
                ['*'],
                'page',
                $queryOption->getPage()
            );
    }

    public function paginated(QueryOption $queryOption, ?string $searchTerm = null, ?string $sortField = null, string $sortOrder = 'asc'): LengthAwarePaginator
    {
        $query = Product::query();

        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        if ($sortField) {
            $query->orderBy($sortField, $sortOrder);
        }

        return $query->paginate(
            $queryOption->getLimit(),
            ['*'],
            'page',
            $queryOption->getPage()
        );
    }

    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data): void
    {
        $product->update($data);
    }
}
