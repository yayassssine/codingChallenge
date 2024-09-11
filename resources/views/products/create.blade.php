@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Product</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" name="price" class="form-control" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="categories">Categories:</label>
                <select name="categories[]" class="form-control" multiple required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Create Product</button>
        </form>
    </div>
@endsection
