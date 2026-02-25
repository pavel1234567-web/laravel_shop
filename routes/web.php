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
// Auth::routes();

// Админ-панель (защищена middleware)
Route::prefix('old-admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', fn() => redirect()->route('admin.products.index'));

    // Товары
    Route::resource('products', AdminProductController::class);

    // Категории
    Route::resource('categories', AdminCategoryController::class);
});
Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
