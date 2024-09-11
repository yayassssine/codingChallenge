<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use YouCanShop\QueryOption\QueryOptionFactory;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $queryOption = QueryOptionFactory::createFromIlluminateRequest($request);

        $query = Product::with('categories');

        if ($queryOption->getSortField()) {
            $query->orderBy($queryOption->getSortField(), $queryOption->getSortOrder());
        }

        if ($filters = $queryOption->getFilters()) {
            foreach ($filters as $filter) {
                if ($filter['field'] === 'category') {
                    $query->whereHas('categories', function ($query) use ($filter) {
                        $query->where('id', $filter['value']);
                    });
                }
            }
        }
        $products = $query->paginate(
            $queryOption->getLimit(),
            ['*'],
            'page',
            $queryOption->getPage()
        );

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'image' => 'nullable|image',
        ]);

        $product = Product::create($request->all());
        $product->categories()->sync($request->categories);

        return redirect()->route('products.index');
    }
}
