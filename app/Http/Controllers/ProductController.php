<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use YouCanShop\QueryOption\QueryOptionFactory;

class ProductController extends Controller
{
    protected $productRepository;
    protected $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $queryOption = QueryOptionFactory::createFromIlluminateRequest($request);

        if ($request->has('filters.category') && $request->input('filters.category') != '') {
            $categoryId = $request->input('filters.category');

            if (!$this->categoryRepository->exists($categoryId)) {
                return redirect()->route('products.index')->withErrors(['category' => 'Invalid category selected']);
            }

            $products = $this->productRepository->filterByCategory($categoryId, $queryOption);
        } else {
            $products = $this->productRepository->paginated($queryOption);
        }

        $categories = $this->categoryRepository->getAll();

        return view('products.index', compact('products', 'categories'));
    }




    public function create()
    {
        $categories = $this->categoryRepository->getAll();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'categories' => 'required|array',
        ]);

        $product = $this->productRepository->createProduct($request->only('name', 'description', 'price'));

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $this->productRepository->updateProductImage($product, $imagePath);
        }

        $this->productRepository->syncCategories($product, $request->categories);

        return redirect()->route('products.index')->with('success', 'Product created!');
    }
}
