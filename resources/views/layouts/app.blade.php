<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Product Store</title>
    <!-- Add your CSS files here -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<!-- Navigation bar -->
<nav>
    <!-- Add your navigation items here -->
    <a href="{{ route('products.index') }}">Products</a>
</nav>

<!-- Main content -->
<main>
    @yield('content')  <!-- This is where child views will inject their content -->
</main>

<!-- Footer -->
<footer>
    <!-- Add footer content here -->
    <p>My Product Store &copy; 2024</p>
</footer>

</body>
</html>
