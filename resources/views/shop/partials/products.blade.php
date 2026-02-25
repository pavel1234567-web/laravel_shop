{{-- Счётчик --}}
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
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                        -{{ $product->discount_percent }}%
                    </span>
                @endif
                <img src="{{ $product->image_url }}"
                     class="card-img-top"
                     style="height:180px; object-fit:cover"
                     alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                    <small class="text-muted">{{ $product->category->name }}</small>
                    <h6 class="card-title mt-1">{{ $product->name }}</h6>
                    <div class="mt-auto">
                        @if($product->old_price)
                            <div class="text-muted text-decoration-line-through small">
                                {{ number_format($product->old_price, 0, '.', ' ') }} ₽
                            </div>
                        @endif
                        <div class="fw-bold text-primary fs-5">
                            {{ number_format($product->price, 0, '.', ' ') }} ₽
                        </div>
                        <a href="{{ route('product', $product->slug) }}"
                           class="btn btn-outline-primary btn-sm mt-2 w-100">
                           Подробнее
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ПАГИНАЦИЯ Bootstrap --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
@endif