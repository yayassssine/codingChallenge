<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use YouCanShop\QueryOption\QueryOption;

class ProductService
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function filterByCategory(int $categoryId, QueryOption $queryOption): LengthAwarePaginator
    {
        $category = $this->categoryRepository->findById($categoryId);

        if (!$category) {
            return new LengthAwarePaginator([], 0, $queryOption->getLimit(), $queryOption->getPage(), [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
        }

        $productIds = $category->products()->pluck('id');

        if ($productIds->isEmpty()) {
            return new LengthAwarePaginator([], 0, $queryOption->getLimit(), $queryOption->getPage(), [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
        }

        $paginatedProducts = $this->productRepository->filterByIds($productIds->toArray(), $queryOption);

        $paginatedProducts->getCollection()->each(function ($product) {
            $product->categories = $product->categories()->get();
        });

        return $paginatedProducts;
    }

    public function paginated(QueryOption $queryOption): LengthAwarePaginator
    {
        $searchTerm = request('q', null);
        $sortField = request('sort_field', null);
        $sortOrder = request('sort_order', 'asc');

        $paginatedProducts = $this->productRepository->paginated($queryOption, $searchTerm, $sortField, $sortOrder);

        $paginatedProducts->getCollection()->each(function ($product) {
            $product->categories = $product->categories()->get();
        });

        return $paginatedProducts;
    }
    public function createProduct(array $data): Product
    {
        return $this->productRepository->createProduct($data);
    }

    public function updateProductImage(Product $product, string $imagePath): void
    {
        $this->productRepository->updateProduct($product, ['image' => $imagePath]);
    }

    public function syncCategories(Product $product, array $categories): void
    {
        $product->categories()->sync($categories);
    }

    public function getAllCategories(): Collection
    {
        $categories = $this->categoryRepository->getAll();
        return $categories;
    }
}
