@extends('layouts.app')
@section('title', 'Каталог')
@section('content')

<div class="container py-4">
    <div class="row">

        {{-- БОКОВОЙ ФИЛЬТР --}}
        <div class="col-lg-3">
            <div class="card p-3 mb-4 shadow-sm">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-funnel text-primary"></i> Фильтры
                </h5>
                <form id="filterForm">

                    <label class="form-label fw-semibold">Категория</label>
                    <select name="category_id" class="form-select form-select-sm mb-3" id="categoryFilter">
                        <option value="">Все категории</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <label class="form-label fw-semibold">Цена от (₽)</label>
                    <input type="number" name="price_min" id="priceMin"
                           class="form-control form-control-sm mb-2"
                           value="{{ request('price_min', $priceRange->min_price) }}"
                           min="{{ $priceRange->min_price }}"
                           max="{{ $priceRange->max_price }}">

                    <label class="form-label fw-semibold">Цена до (₽)</label>
                    <input type="number" name="price_max" id="priceMax"
                           class="form-control form-control-sm mb-3"
                           value="{{ request('price_max', $priceRange->max_price) }}"
                           min="{{ $priceRange->min_price }}"
                           max="{{ $priceRange->max_price }}">

                    <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">
                        <i class="bi bi-funnel"></i> Применить
                    </button>
                    <button type="button" id="resetBtn" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x"></i> Сбросить
                    </button>
                </form>
            </div>
        </div>

        {{-- ОСНОВНОЙ КОНТЕНТ --}}
        <div class="col-lg-9">

            {{-- Поиск и сортировка --}}
            <div class="d-flex gap-2 mb-4 flex-wrap">
                <input type="text" id="searchInput" class="form-control"
                       placeholder="Поиск товаров..."
                       value="{{ request('search') }}">

                <select id="sortSelect" class="form-select" style="width:200px">
                    <option value="newest"     {{ request('sort','newest')=='newest'     ? 'selected':'' }}>Новинки</option>
                    <option value="price_asc"  {{ request('sort')=='price_asc'           ? 'selected':'' }}>Цена ↑</option>
                    <option value="price_desc" {{ request('sort')=='price_desc'          ? 'selected':'' }}>Цена ↓</option>
                    <option value="name_asc"   {{ request('sort')=='name_asc'            ? 'selected':'' }}>Название А-Я</option>
                    <option value="name_desc"  {{ request('sort')=='name_desc'           ? 'selected':'' }}>Название Я-А</option>
                </select>

                <!-- <button id="searchBtn" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button> -->
            </div>

            {{-- Спиннер загрузки --}}
            <div id="loadingSpinner" class="text-center py-5 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Загрузка...</span>
                </div>
                <p class="mt-2 text-muted">Загрузка товаров...</p>
            </div>

            {{-- Контейнер товаров --}}
            <div id="productsContainer">
                @include('shop.partials.products')
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const catalogUrl = '{{ route("catalog") }}';
    let searchTimer = null;

    function getParams(page = 1) {
        return {
            search:      document.getElementById('searchInput').value.trim(),
            sort:        document.getElementById('sortSelect').value,
            category_id: document.getElementById('categoryFilter').value,
            price_min:   document.getElementById('priceMin').value,
            price_max:   document.getElementById('priceMax').value,
            page:        page,
        };
    }

    function loadProducts(page = 1) {
        const params    = getParams(page);
        const spinner   = document.getElementById('loadingSpinner');
        const container = document.getElementById('productsContainer');

        spinner.classList.remove('d-none');
        container.style.opacity = '0.4';
        container.style.pointerEvents = 'none';

        const urlParams = new URLSearchParams(params);
        window.history.pushState({}, '', '?' + urlParams.toString());

        fetch(catalogUrl + '?' + urlParams.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',  // ← обязательно
            }
        })
        .then(res => res.json())
        .then(data => {
            container.innerHTML = data.html;
            spinner.classList.add('d-none');
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
            bindPagination();
        })
        .catch(err => {
            console.error('Ошибка:', err);
            spinner.classList.add('d-none');
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        });
    }

    function bindPagination() {
        document.querySelectorAll('#productsContainer .pagination a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url  = new URL(this.href);
                const page = url.searchParams.get('page') || 1;
                loadProducts(page);
            });
        });
    }

    // Фильтр — кнопка Применить
    document.getElementById('filterForm').addEventListener('submit', function (e) {
        e.preventDefault();
        loadProducts(1);
    });

    // Сортировка — сразу
    document.getElementById('sortSelect').addEventListener('change', () => loadProducts(1));

    // Категория — сразу
    document.getElementById('categoryFilter').addEventListener('change', () => loadProducts(1));

    // ↓ ЦЕНА — при потере фокуса (новое)
    document.getElementById('priceMin').addEventListener('change', () => loadProducts(1));
    document.getElementById('priceMax').addEventListener('change', () => loadProducts(1));

    // Поиск — с задержкой
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => loadProducts(1), 500);
    });

    // ↓ ПОИСК — по Enter (новое)
    document.getElementById('searchInput').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimer);
            loadProducts(1);
        }
    });

    // Кнопка поиска
    document.getElementById('searchBtn').addEventListener('click', () => loadProducts(1));

    // Сброс
    document.getElementById('resetBtn').addEventListener('click', function () {
        document.getElementById('searchInput').value    = '';
        document.getElementById('sortSelect').value     = 'newest';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('priceMin').value = '{{ $priceRange->min_price }}';
        document.getElementById('priceMax').value = '{{ $priceRange->max_price }}';
        loadProducts(1);
    });

    bindPagination();
</script>
@endsection