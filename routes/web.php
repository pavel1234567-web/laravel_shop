<?php

/**
 * Файл веб-маршрутов приложения
 * Определяет все HTTP маршруты для веб-интерфейса
 */

// Импорт контроллеров
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Маршруты магазина (публичная часть)
|--------------------------------------------------------------------------
*/

// Главная страница магазина
Route::get('/', [ShopController::class, 'index'])->name('home');

// Каталог товаров
Route::get('/catalog', [ShopController::class, 'catalog'])->name('catalog');

// Страница категории с фильтром по slug
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category');

// Страница отдельного товара по slug
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('product');


// Route::get('/test-mail', function () {
//     Mail::raw('Test message', function($m) {
//         $m->to('mega-sergey845@ukr.net')->subject('Test');
//     });
//     return 'Sent!';
// });

/*
|--------------------------------------------------------------------------
| Маршруты старой административной панели (устаревшая)
|--------------------------------------------------------------------------
|
| Доступны только авторизованным пользователям (middleware 'auth')
| Префикс URL: /old-admin, название маршрутов: admin.*
|
*/
// Route::prefix('old-admin')->name('admin.')->middleware(['auth'])->group(function () {
//     // Редирект с /old-admin на список товаров
//     Route::get('/', fn() => redirect()->route('admin.products.index'));

//     // CRUD операции для товаров
//     Route::resource('products', AdminProductController::class);

//     // CRUD операции для категорий
//     Route::resource('categories', AdminCategoryController::class);
// });

/*
|--------------------------------------------------------------------------
| Маршруты аутентификации Laravel
|--------------------------------------------------------------------------
|
| Регистрирует все стандартные маршруты для входа, регистрации,
| восстановления пароля и т.д.
|
*/
// Auth::routes();

Auth::routes(['verify' => true]);

// Закомментированный маршрут домашней страницы после входа
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');