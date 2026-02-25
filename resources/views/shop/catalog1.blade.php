@extends('layouts.app')
@section('title', 'Каталог')
@section('content')
<div class="container py-4">
    <div class="row">

        {{-- БОКОВОЙ ФИЛЬТР --}}
        <div class="col-lg-3">
            <div class="card p-3 mb-4">
                <h5 class="fw-bold">Фильтры</h5>
                <form action="{{ route('catalog') }}" method="GET" id="filterForm">

                    {{-- Сохраняем поиск и сортировку --}}
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <label class="form-label fw-semibold mt-2">Категория</label>
                    <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Все категории</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <label class="form-label fw-semibold mt-3">Цена от (₽)</label>
                    <input type="number" name="price_min" class="form-control form-control-sm"
                           value="{{ request('price_min', $priceRange->min_price) }}"
                           min="{{ $priceRange->min_price }}" max="{{ $priceRange->max_price }}">

                    <label class="form-label fw-semibold mt-2">Цена до (₽)</label>
                    <input type="number" name="price_max" class="form-control form-control-sm"
                           value="{{ request('price_max', $priceRange->max_price) }}"
                           min="{{ $priceRange->min_price }}" max="{{ $priceRange->max_price }}">

                    <button type="submit" class="btn btn-primary btn-sm w-100 mt-3">
                        <i class="bi bi-funnel"></i> Применить
                    </button>
                    <a href="{{ route('catalog') }}" class="btn btn-outline-secondary btn-sm w-100 mt-1">
                        <i class="bi bi-x"></i> Сбросить
                    </a>
                </form>
            </div>
        </div>

        {{-- ОСНОВНОЙ КОНТЕНТ --}}
        <div class="col-lg-9">

            {{-- Поиск и сортировка --}}
            <div class="d-flex gap-2 mb-4 flex-wrap">
                <form action="{{ route('catalog') }}" method="GET" class="d-flex flex-grow-1 gap-2">
                    @if(request('category_id')) <input type="hidden" name="category_id" value="{{ request('category_id') }}"> @endif
                    @if(request('price_min'))   <input type="hidden" name="price_min"   value="{{ request('price_min') }}"> @endif
                    @if(request('price_max'))   <input type="hidden" name="price_max"   value="{{ request('price_max') }}"> @endif

                    <input type="text" name="search" class="form-control" placeholder="Поиск товаров..."
                           value="{{ request('search') }}">

                    <select name="sort" class="form-select" style="width:200px">
                        <option value="newest"     {{ request('sort','newest')=='newest'     ? 'selected' : '' }}>Новинки</option>
                        <option value="price_asc"  {{ request('sort')=='price_asc'           ? 'selected' : '' }}>Цена ↑</option>
                        <option value="price_desc" {{ request('sort')=='price_desc'          ? 'selected' : '' }}>Цена ↓</option>
                        <option value="name_asc"   {{ request('sort')=='name_asc'            ? 'selected' : '' }}>Название А-Я</option>
                        <option value="name_desc"  {{ request('sort')=='name_desc'           ? 'selected' : '' }}>Название Я-А</option>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>

            <p class="text-muted">Найдено: {{ $products->total() }} товаров</p>

            @if($products->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted"></i>
                    <p class="mt-3">Товары не найдены. Попробуйте изменить параметры поиска.</p>
                </div>
            @else
            <div class="row g-4">
                @foreach($products as $product)
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 product-card position-relative">
                        @if($product->discount_percent)
                            <span class="badge bg-danger badge-discount">-{{ $product->discount_percent }}%</span>
                        @endif
                        <img src="{{ $product->image_url }}"
                             class="card-img-top" style="height:180px;object-fit:cover" alt="{{ $product->name }}">
                        <div class="card-body d-flex flex-column">
                            <small class="text-muted">{{ $product->category->name }}</small>
                            <h6 class="card-title mt-1">{{ $product->name }}</h6>
                            <div class="mt-auto">
                                @if($product->old_price)
                                    <div class="price-old">{{ number_format($product->old_price, 0, '.', ' ') }} ₽</div>
                                @endif
                                <div class="fw-bold text-primary fs-5">{{ number_format($product->price, 0, '.', ' ') }} ₽</div>
                                <a href="{{ route('product', $product->slug) }}"
                                   class="btn btn-outline-primary btn-sm mt-2 w-100">Подробнее</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ПАГИНАЦИЯ --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection