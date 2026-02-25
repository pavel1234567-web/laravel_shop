@extends('layouts.app')
@section('title', $category->name)
@section('content')

<div class="container py-4">

    {{-- Хлебные крошки --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Главная</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('catalog') }}">Каталог</a>
            </li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    {{-- Заголовок категории --}}
    <div class="mb-4">
        <h1 class="h2 fw-bold">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-muted">{{ $category->description }}</p>
        @endif
        <span class="badge bg-primary">{{ $products->total() }} товаров</span>
    </div>

    {{-- Товары --}}
    @if($products->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="mt-3 text-muted">В этой категории пока нет товаров</p>
            <a href="{{ route('catalog') }}" class="btn btn-primary mt-2">
                Перейти в каталог
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 product-card position-relative">

                    {{-- Скидка --}}
                    @if($product->old_price && $product->old_price > $product->price)
                        @php
                            $discount = round((1 - $product->price / $product->old_price) * 100);
                        @endphp
                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                            -{{ $discount }}%
                        </span>
                    @endif

                    <img src="{{ $product->image_url }}"
                         class="card-img-top"
                         style="height:200px; object-fit:cover"
                         alt="{{ $product->name }}">

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $product->name }}</h6>

                        <p class="text-muted small">
                            {{ Str::limit($product->description, 80) }}
                        </p>

                        <div class="mt-auto">
                            @if($product->old_price)
                                <div class="text-muted text-decoration-line-through small">
                                    {{ number_format($product->old_price, 0, '.', ' ') }} ₽
                                </div>
                            @endif
                            <div class="fw-bold text-primary fs-5">
                                {{ number_format($product->price, 0, '.', ' ') }} ₽
                            </div>

                            <div class="d-flex gap-2 mt-2">
                                <a href="{{ route('product', $product->slug) }}"
                                   class="btn btn-outline-primary btn-sm flex-grow-1">
                                    Подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Пагинация --}}
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    @endif

</div>
@endsection
