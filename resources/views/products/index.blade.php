@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Product List</h1>
    <form method="GET" action="{{ route('products.index') }}">
        <input type="text" name="q" placeholder="Search Products" value="{{ request('q') }}">
        <select name="sort_field">
            <option value="price" {{ request('sort_field') === 'price' ? 'selected' : '' }}>Price</option>
            <option value="name" {{ request('sort_field') === 'name' ? 'selected' : '' }}>Name</option>
            <option value="created_at" {{ request('sort_field') === 'created_at' ? 'selected' : '' }}>Date Added</option>
        </select>
        <select name="sort_order">
            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
        </select>
        <select name="filters[category]">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('filters.category') === $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button type="submit">Apply Filters</button>
    </form>
    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
            <th>Date Added</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
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
        @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endsection
