<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use YouCanShop\QueryOption\QueryOptionFactory;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        if ($request->has('filters.category') && is_string($request->input('filters.category'))) {
            $categoryId = $request->input('filters.category');
            $request->merge([
                'filters' => [
                    'category' => ['field' => 'category', 'value' => $categoryId]
                ]
            ]);
        }
        $queryOption = QueryOptionFactory::createFromIlluminateRequest($request);
        $products = $this->productRepository->paginated($queryOption);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
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
        $product = Product::create($request->only('name', 'description', 'price'));
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $product->update(['image' => $imagePath]);
        }
        $product->categories()->sync($request->categories);
        return redirect()->route('products.index')->with('success', 'Product created!');
    }

}
