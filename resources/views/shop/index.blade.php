@extends('layouts.app')
@section('title', 'Главная — ShopLaravel')
@section('content')

<div class="bg-primary text-white py-5 mb-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">Добро пожаловать в ShopLaravel</h1>
        <p class="lead">Лучшие товары по лучшим ценам</p>
        <a href="{{ route('catalog') }}" class="btn btn-light btn-lg">Перейти в каталог</a>
    </div>
</div>

<div class="container">
    <h2 class="mb-4">Категории</h2>
    <div class="row g-3 mb-5">
        @foreach($categories as $cat)
        <div class="col-md-4 col-lg-2">
            <a href="{{ route('category', $cat->slug) }}" class="text-decoration-none">
                <div class="card text-center p-3 h-100 product-card">
                    <div class="card-body">
                        <i class="bi bi-tag fs-2 text-primary"></i>
                        <p class="card-title mt-2 fw-bold">{{ $cat->name }}</p>
                        <small class="text-muted">{{ $cat->products_count }} товаров</small>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <h2 class="mb-4">Новые поступления</h2>
    <div class="row g-4">
        @foreach($featuredProducts as $product)
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 product-card position-relative">
                @if($product->discount_percent)
                    <span class="badge bg-danger badge-discount">-{{ $product->discount_percent }}%</span>
                @endif
                <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200?text=' . urlencode($product->name) }}"
                     class="card-img-top" style="height:200px;object-fit:cover" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                    <small class="text-muted">{{ $product->category->name }}</small>
                    <h6 class="card-title mt-1">{{ $product->name }}</h6>
                    <div class="mt-auto">
                        @if($product->old_price)
                            <span class="price-old">{{ number_format($product->old_price, 0, '.', ' ') }} ₽</span>
                        @endif
                        <div class="fw-bold text-primary fs-5">{{ number_format($product->price, 0, '.', ' ') }} ₽</div>
                        <a href="{{ route('product', $product->slug) }}" class="btn btn-primary btn-sm mt-2 w-100">Подробнее</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection