<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Admin — @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <div class="admin-sidebar d-flex flex-column p-3" style="width:250px;min-height:100vh;background:#2c3e50">
        <a href="/" class="text-white text-decoration-none mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-shop-window fs-4"></i> <span class="fw-bold">ShopAdmin</span>
        </a>
        <nav class="nav flex-column gap-1">
            <a href="{{ route('admin.products.index') }}"
               class="nav-link text-white rounded {{ request()->routeIs('admin.products*') ? 'bg-primary' : '' }}">
                <i class="bi bi-box-seam"></i> Товары
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="nav-link text-white rounded {{ request()->routeIs('admin.categories*') ? 'bg-primary' : '' }}">
                <i class="bi bi-tags"></i> Категории
            </a>
            <a href="{{ route('home') }}" class="nav-link text-white rounded mt-3">
                <i class="bi bi-arrow-left"></i> На сайт
            </a>
        </nav>
    </div>

    <div class="flex-grow-1 p-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
