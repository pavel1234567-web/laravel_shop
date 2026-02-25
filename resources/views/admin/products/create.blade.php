@extends('layouts.admin')
@section('title', 'Добавить товар')
@section('content')
<h2 class="mb-4">Добавить товар</h2>

<div class="card p-4" style="max-width:700px">
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Название *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Категория *</label>
            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                <option value="">Выберите категорию</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Цена (₽) *</label>
                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                       value="{{ old('price') }}" step="0.01" min="0" required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Старая цена (₽)</label>
                <input type="number" name="old_price" class="form-control"
                       value="{{ old('old_price') }}" step="0.01" min="0">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Количество на складе *</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Описание</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" checked>
            <label for="is_active" class="form-check-label">Активен (виден на сайте)</label>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Сохранить
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
</div>
@endsection