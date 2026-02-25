@extends('layouts.app')
@section('title', $product->name)
@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Каталог</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            {{-- КАРТИНКА --}}
            <div class="col-md-5">
                <div class="position-relative">
                    {{-- ↓ image_url из модели --}}
                    <img src="{{ $product->image_url }}" class="img-fluid rounded shadow w-100"
                        style="max-height:450px; object-fit:cover" alt="{{ $product->name }}">

                    @if($product->discount_percent)
                        <span class="badge bg-danger position-absolute top-0 end-0 m-3 fs-6">
                            -{{ $product->discount_percent }}%
                        </span>
                    @endif
                </div>
            </div>
            {{-- ИНФОРМАЦИЯ --}}
            <div class="col-md-7">
                <h1 class="h2">{{ $product->name }}</h1>
                <p class="text-muted">Категория: <a
                        href="{{ route('category', $product->category->slug) }}">{{ $product->category->name }}</a></p>
                <hr>
                @if($product->old_price)
                    <div class="price-old fs-5">{{ number_format($product->old_price, 0, '.', ' ') }} ₽</div>
                @endif
                <div class="fw-bold text-primary display-6 mb-3">{{ number_format($product->price, 0, '.', ' ') }} ₽</div>
                @if($product->discount_percent)
                    <span class="badge bg-danger fs-6">Скидка {{ $product->discount_percent }}%</span>
                @endif
                <p class="mt-3">{{ $product->description }}</p>
                <p class="text-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                    <i class="bi bi-circle-fill"></i>
                    {{ $product->stock > 0 ? 'В наличии (' . $product->stock . ' шт.)' : 'Нет в наличии' }}
                </p>
                <button class="btn btn-primary btn-lg mt-2">
                    <i class="bi bi-cart-plus"></i> Добавить в корзину
                </button>
            </div>
        </div>
    </div>
@endsection