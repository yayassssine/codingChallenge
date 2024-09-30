<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\ValidationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use YouCanShop\QueryOption\QueryOptionFactory;

class ProductController extends Controller
{
    private ProductService $productService;
    private ValidationService $validationService;

    public function __construct(ProductService $productService, ValidationService $validationService)
    {
        $this->productService = $productService;
        $this->validationService = $validationService;
    }

    public function index(Request $request): View
    {
        $queryOption = QueryOptionFactory::createFromIlluminateRequest($request);

        $searchTerm = $request->input('q', null);
        $sortField = $request->input('sort_field', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        $categoryId = $request->input('filters.category', null);

        if ($categoryId) {
            $products = $this->productService->filterByCategory((int)$categoryId, $queryOption);
        } else {
            $products = $this->productService->paginated($queryOption);
        }

        $categories = $this->productService->getAllCategories();

        return view('products.index', compact('products', 'categories', 'searchTerm', 'sortField', 'sortOrder', 'categoryId'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $this->validationService->validateProduct($request->all());
            $product = $this->productService->createProduct($request->only('name', 'description', 'price'));

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $this->productService->updateProductImage($product, $imagePath);
            }

            $this->productService->syncCategories($product, $request->categories);

            return redirect()->route('products.index')->with('success', 'Product created!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        }
    }
}
