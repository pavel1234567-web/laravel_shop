@extends('layouts.admin')
@section('title', 'Товары')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Управление товарами</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Добавить товар
    </a>
</div>

<form action="{{ route('admin.products.index') }}" method="GET" class="mb-3">
    <div class="input-group" style="max-width:400px">
        <input type="text" name="search" class="form-control" placeholder="Поиск..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th><th>Название</th><th>Категория</th>
                    <th>Цена</th><th>Склад</th><th>Статус</th><th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td><span class="badge bg-secondary">{{ $product->category->name }}</span></td>
                    <td class="fw-bold">{{ number_format($product->price, 0, '.', ' ') }} ₽</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                            {{ $product->is_active ? 'Активен' : 'Скрыт' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Удалить товар?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection