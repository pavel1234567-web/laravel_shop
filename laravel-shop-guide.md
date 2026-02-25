# 🛒 Интернет-магазин на Laravel — Полное пошаговое руководство

---

## СОДЕРЖАНИЕ

1. Установка Laravel и настройка
2. База данных — структура и SQL-запросы
3. Модели и миграции
4. Сидеры (заполнение данными через SQL)
5. Роутинг
6. Контроллеры (магазин)
7. Views (шаблоны Blade)
8. Поиск, сортировка, фильтрация, пагинация
9. Админ-панель (CRUD)
10. Итоговая структура проекта

---

## ШАГ 1 — УСТАНОВКА LARAVEL

```bash
# Установить через Composer
composer create-project laravel/laravel shop "10.*"
cd shop

# Настроить .env
cp .env.example .env
php artisan key:generate
```

Откройте `.env` и настройте БД:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shop_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

Создайте базу данных в MySQL:

```sql
CREATE DATABASE shop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## ШАГ 2 — СТРУКТУРА БАЗЫ ДАННЫХ

### Таблицы:
- `categories` — категории товаров
- `products` — товары
- `users` — пользователи (встроено в Laravel)

### SQL для создания таблиц (вручную или через миграции):

```sql
-- Таблица категорий
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    image VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Таблица товаров
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    old_price DECIMAL(10,2) NULL,
    image VARCHAR(255) NULL,
    stock INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Добавить индексы для поиска
CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_products_price ON products(price);
CREATE INDEX idx_products_category ON products(category_id);
```

### SQL для заполнения данными:

```sql
-- Категории
INSERT INTO categories (name, slug, description, created_at, updated_at) VALUES
('Электроника', 'elektronika', 'Смартфоны, ноутбуки, планшеты', NOW(), NOW()),
('Одежда', 'odezhda', 'Мужская и женская одежда', NOW(), NOW()),
('Спорт', 'sport', 'Спортивные товары и инвентарь', NOW(), NOW()),
('Книги', 'knigi', 'Художественная и учебная литература', NOW(), NOW()),
('Дом и сад', 'dom-i-sad', 'Товары для дома и сада', NOW(), NOW());

-- Товары
INSERT INTO products (category_id, name, slug, description, price, old_price, stock, is_active, created_at, updated_at) VALUES
(1, 'iPhone 15 Pro', 'iphone-15-pro', 'Флагманский смартфон Apple с чипом A17 Pro', 89999.00, 99999.00, 15, 1, NOW(), NOW()),
(1, 'Samsung Galaxy S24', 'samsung-galaxy-s24', 'Флагман Samsung с AI функциями', 74999.00, NULL, 20, 1, NOW(), NOW()),
(1, 'Ноутбук ASUS VivoBook 15', 'asus-vivobook-15', 'Ноутбук для работы и учёбы', 49999.00, 54999.00, 8, 1, NOW(), NOW()),
(1, 'iPad Air 5', 'ipad-air-5', 'Планшет Apple с чипом M1', 59999.00, NULL, 12, 1, NOW(), NOW()),
(1, 'Sony WH-1000XM5', 'sony-wh-1000xm5', 'Беспроводные наушники с шумодавлением', 24999.00, 27999.00, 30, 1, NOW(), NOW()),
(2, 'Футболка Nike Dri-FIT', 'futbolka-nike-dri-fit', 'Спортивная футболка из дышащей ткани', 2999.00, NULL, 50, 1, NOW(), NOW()),
(2, 'Джинсы Levi''s 501', 'dzhinsy-levis-501', 'Классические прямые джинсы', 5999.00, 7999.00, 25, 1, NOW(), NOW()),
(3, 'Кроссовки Adidas Ultraboost', 'krossovki-adidas-ultraboost', 'Беговые кроссовки с технологией Boost', 12999.00, 14999.00, 18, 1, NOW(), NOW()),
(3, 'Гантели разборные 20 кг', 'ganteli-razbornye-20kg', 'Комплект разборных гантелей', 4999.00, NULL, 10, 1, NOW(), NOW()),
(4, 'Война и мир — Толстой', 'voyna-i-mir-tolstoy', 'Классический роман в 2 томах', 899.00, NULL, 100, 1, NOW(), NOW()),
(4, 'Clean Code — Роберт Мартин', 'clean-code-martin', 'Книга о написании чистого кода', 1299.00, 1599.00, 40, 1, NOW(), NOW()),
(5, 'Кофемашина DeLonghi', 'kofemashina-delonghi', 'Автоматическая кофемашина с капучинатором', 34999.00, 39999.00, 5, 1, NOW(), NOW()),
(5, 'Пылесос Dyson V15', 'pylesos-dyson-v15', 'Беспроводной пылесос с лазерным обнаружением', 49999.00, NULL, 7, 1, NOW(), NOW()),
(1, 'Xiaomi Redmi Note 13', 'xiaomi-redmi-note-13', 'Бюджетный смартфон с отличной камерой', 18999.00, 21999.00, 35, 1, NOW(), NOW()),
(3, 'Велосипед горный 26"', 'velosiped-gorny-26', 'Горный велосипед для бездорожья', 19999.00, NULL, 6, 1, NOW(), NOW());
```

---

## ШАГ 3 — МИГРАЦИИ LARAVEL

Вместо ручного SQL можно использовать миграции:

```bash
php artisan make:migration create_categories_table
php artisan make:migration create_products_table
```

`database/migrations/xxxx_create_categories_table.php`:

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('categories');
    }
};
```

`database/migrations/xxxx_create_products_table.php`:

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('products');
    }
};
```

```bash
php artisan migrate
```

---

## ШАГ 4 — МОДЕЛИ

```bash
php artisan make:model Category
php artisan make:model Product
```

`app/Models/Category.php`:

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $fillable = ['name', 'slug', 'description', 'image'];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
```

`app/Models/Product.php`:

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'old_price', 'image', 'stock', 'is_active'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function getDiscountPercentAttribute(): ?int {
        if ($this->old_price && $this->old_price > $this->price) {
            return round((1 - $this->price / $this->old_price) * 100);
        }
        return null;
    }
}
```

---

## ШАГ 5 — СИДЕРЫ (заполнение через Eloquent + SQL)

```bash
php artisan make:seeder CategorySeeder
php artisan make:seeder ProductSeeder
```

`database/seeders/CategorySeeder.php`:

```php
<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder {
    public function run(): void {
        DB::statement("
            INSERT INTO categories (name, slug, description, created_at, updated_at) VALUES
            ('Электроника', 'elektronika', 'Смартфоны, ноутбуки, планшеты', NOW(), NOW()),
            ('Одежда', 'odezhda', 'Мужская и женская одежда', NOW(), NOW()),
            ('Спорт', 'sport', 'Спортивные товары и инвентарь', NOW(), NOW()),
            ('Книги', 'knigi', 'Художественная и учебная литература', NOW(), NOW()),
            ('Дом и сад', 'dom-i-sad', 'Товары для дома и сада', NOW(), NOW())
        ");
    }
}
```

`database/seeders/DatabaseSeeder.php`:

```php
<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
```

```bash
php artisan db:seed
```

---

## ШАГ 6 — РОУТИНГ

`routes/web.php`:

```php
<?php
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use Illuminate\Support\Facades\Route;

// Магазин
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/catalog', [ShopController::class, 'catalog'])->name('catalog');
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('product');

// Авторизация (встроенная)
Auth::routes();

// Админ-панель (защищена middleware)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', fn() => redirect()->route('admin.products.index'));
    
    // Товары
    Route::resource('products', AdminProductController::class);
    
    // Категории
    Route::resource('categories', AdminCategoryController::class);
});
```

---

## ШАГ 7 — КОНТРОЛЛЕР МАГАЗИНА

```bash
php artisan make:controller ShopController
```

`app/Http/Controllers/ShopController.php`:

```php
<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller {

    public function index() {
        $categories = Category::withCount('products')->get();
        $featuredProducts = Product::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
        return view('shop.index', compact('categories', 'featuredProducts'));
    }

    public function catalog(Request $request) {
        $query = Product::with('category')->where('is_active', 1);

        // --- ПОИСК ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // --- ФИЛЬТР ПО КАТЕГОРИИ ---
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // --- ФИЛЬТР ПО ДИАПАЗОНУ ЦЕНЫ ---
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // --- СОРТИРОВКА ---
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc'   => $query->orderBy('name', 'asc'),
            'name_desc'  => $query->orderBy('name', 'desc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        // --- ПАГИНАЦИЯ ---
        $products = $query->paginate(9)->withQueryString();

        $categories = Category::all();
        $priceRange = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        return view('shop.catalog', compact('products', 'categories', 'priceRange'));
    }

    public function category($slug) {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->where('is_active', 1)
            ->paginate(9);
        return view('shop.category', compact('category', 'products'));
    }

    public function product($slug) {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', 1)
            ->limit(4)->get();
        return view('shop.product', compact('product', 'related'));
    }
}
```

---

## ШАГ 8 — КОНТРОЛЛЕРЫ АДМИН-ПАНЕЛИ

```bash
php artisan make:controller Admin/AdminProductController --resource
php artisan make:controller Admin/AdminCategoryController --resource
```

`app/Http/Controllers/Admin/AdminProductController.php`:

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller {

    public function index(Request $request) {
        $query = Product::with('category');
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }
        $products = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function create() {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'is_active'   => 'boolean',
        ]);
        $data['slug'] = Str::slug($data['name']) . '-' . time();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Товар добавлен!');
    }

    public function edit(Product $product) {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product) {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Товар обновлён!');
    }

    public function destroy(Product $product) {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Товар удалён!');
    }
}
```

---

## ШАГ 9 — VIEWS (ШАБЛОНЫ)

### Базовый layout `resources/views/layouts/app.blade.php`:

```html
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Магазин')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .product-card { transition: transform 0.2s; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        .badge-discount { position: absolute; top: 10px; right: 10px; }
        .price-old { text-decoration: line-through; color: #999; font-size: 0.85rem; }
        .admin-sidebar { min-height: 100vh; background: #2c3e50; }
        .admin-sidebar a { color: #ecf0f1; }
        .admin-sidebar a:hover { color: #3498db; background: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="bi bi-shop"></i> ShopLaravel
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="{{ route('catalog') }}">Каталог</a>
            @auth
                <a class="nav-link" href="{{ route('admin.products.index') }}">Админ</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-link nav-link">Выйти</button>
                </form>
            @else
                <a class="nav-link" href="{{ route('login') }}">Войти</a>
            @endauth
        </div>
    </div>
</nav>

<main>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible m-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @yield('content')
</main>

<footer class="bg-dark text-light py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">© 2024 ShopLaravel. Все права защищены.</p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
```

### Главная страница `resources/views/shop/index.blade.php`:

```html
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
```

### Каталог `resources/views/shop/catalog.blade.php`:

```html
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
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200?text=' . urlencode($product->name) }}"
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
```

### Страница товара `resources/views/shop/product.blade.php`:

```html
@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Каталог</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-5">
            <img src="{{ $product->image ?? 'https://via.placeholder.com/500x400?text=' . urlencode($product->name) }}"
                 class="img-fluid rounded shadow" alt="{{ $product->name }}">
        </div>
        <div class="col-md-7">
            <h1 class="h2">{{ $product->name }}</h1>
            <p class="text-muted">Категория: <a href="{{ route('category', $product->category->slug) }}">{{ $product->category->name }}</a></p>
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
```

---

## ШАГ 10 — АДМИН-ПАНЕЛЬ VIEWS

### Layout для админки `resources/views/layouts/admin.blade.php`:

```html
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
```

### Список товаров `resources/views/admin/products/index.blade.php`:

```html
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
```

### Форма создания товара `resources/views/admin/products/create.blade.php`:

```html
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
```

---

## ШАГ 11 — ФИНАЛЬНЫЕ КОМАНДЫ

```bash
# 1. Создать таблицы аутентификации
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run build

# 2. Запустить миграции
php artisan migrate

# 3. Заполнить данными
php artisan db:seed

# 4. Создать первого admin-пользователя через tinker
php artisan tinker
>>> App\Models\User::create([
...     'name' => 'Admin',
...     'email' => 'admin@shop.ru',
...     'password' => bcrypt('password123')
... ]);

# 5. Запустить сервер
php artisan serve
# Открыть: http://localhost:8000
# Админка:  http://localhost:8000/admin
```

---

## ИТОГОВАЯ СТРУКТУРА ПРОЕКТА

```
shop/
├── app/
│   ├── Http/Controllers/
│   │   ├── ShopController.php          ← каталог, поиск, фильтрация
│   │   └── Admin/
│   │       ├── AdminProductController.php
│   │       └── AdminCategoryController.php
│   └── Models/
│       ├── Category.php
│       └── Product.php
├── database/
│   ├── migrations/
│   │   ├── create_categories_table.php
│   │   └── create_products_table.php
│   └── seeders/
│       ├── CategorySeeder.php
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php               ← основной layout
│   │   └── admin.blade.php             ← layout админки
│   ├── shop/
│   │   ├── index.blade.php             ← главная
│   │   ├── catalog.blade.php           ← каталог + фильтры
│   │   └── product.blade.php           ← страница товара
│   └── admin/
│       └── products/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
└── routes/
    └── web.php
```

---

## ФУНКЦИОНАЛ РЕАЛИЗОВАН ✅

| Функция | Реализация |
|---|---|
| **Категории товаров** | Модель Category, роут `/category/{slug}` |
| **Каталог товаров** | Роут `/catalog`, пагинация 9 товаров |
| **Поиск** | GET-параметр `search`, LIKE запрос |
| **Сортировка** | `sort=price_asc/price_desc/name_asc/newest` |
| **Фильтр по цене** | `price_min` и `price_max` параметры |
| **Пагинация** | `paginate(9)->withQueryString()` |
| **База данных** | MySQL + Миграции + SQL-сидеры |
| **Админ-панель** | CRUD для товаров и категорий |
| **Авторизация** | Laravel Auth middleware |
