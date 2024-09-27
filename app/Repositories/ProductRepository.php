<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use YouCanShop\QueryOption\Laravel\UsesQueryOption;
use YouCanShop\QueryOption\QueryOption;

class ProductRepository
{
    use UsesQueryOption;
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function filterByCategory($categoryId, QueryOption $queryOption): LengthAwarePaginator
    {
        $productIds = $this->categoryRepository->getProductIdsByCategory($categoryId);

        $query = Product::query()->whereIn('id', $productIds);

        return $query->with('categories')->paginate(
            $queryOption->getLimit(),
            ['*'],
            'page',
            $queryOption->getPage()
        );
    }

    public function paginated(QueryOption $queryOption): LengthAwarePaginator
    {
        $query = Product::query();

        if ($searchTerm = request('q')) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        if ($sortField = request('sort_field')) {
            $sortOrder = request('sort_order', 'asc');
            $query->orderBy($sortField, $sortOrder);
        }

        return $query->with('categories')->paginate(
            $queryOption->getLimit(),
            ['*'],
            'page',
            $queryOption->getPage()
        );
    }


    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function updateProductImage(Product $product, string $imagePath)
    {
        $product->update(['image' => $imagePath]);
    }

    public function syncCategories(Product $product, array $categories)
    {
        $product->categories()->sync($categories);
    }
}
