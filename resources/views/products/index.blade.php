@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Product List</h1>
        <form method="GET" action="{{ route('products.index') }}" class="mb-3">
            <div class="form-group row">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="q" placeholder="Search Products" value="{{ request('q') }}">
                </div>

                <div class="col-md-2">
                    <select name="sort_field" class="form-control">
                        <option value="price" {{ request('sort_field') === 'price' ? 'selected' : '' }}>Price</option>
                        <option value="name" {{ request('sort_field') === 'name' ? 'selected' : '' }}>Name</option>
                        <option value="created_at" {{ request('sort_field') === 'created_at' ? 'selected' : '' }}>Date Added</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="sort_order" class="form-control">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="filters[category]" class="form-control">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('filters.category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Date Added</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>${{ $product->price }}</td>
                    <td>
                        @foreach($product->categories as $category)
                            {{ $category->name }}
                        @endforeach
                    </td>
                    <td>{{ $product->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No products found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
@endsection
