<?php

// Импорт контроллеров аутентификации
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/**
 * Маршруты для гостей (неавторизованных пользователей)
 * Доступны только когда пользователь не вошел в систему
 */
Route::middleware('guest')->group(function () {
    // Регистрация - отображение формы
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    // Регистрация - обработка отправки формы
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Вход - отображение формы
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // Вход - обработка отправки формы
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Восстановление пароля - отображение формы запроса
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    // Восстановление пароля - отправка ссылки на email
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // Сброс пароля - отображение формы с токеном
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    // Сброс пароля - сохранение нового пароля
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

/**
 * Маршруты для авторизованных пользователей
 * Доступны только когда пользователь вошел в систему
 */
Route::middleware('auth')->group(function () {
    // Верификация email - уведомление о необходимости подтверждения
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    // Верификация email - проверка подписи ссылки
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1']) // Подписанный URL и ограничение запросов
        ->name('verification.verify');

    // Верификация email - повторная отправка ссылки
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1') // Ограничение: 6 попыток в минуту
        ->name('verification.send');

    // Подтверждение пароля - отображение формы
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    // Подтверждение пароля - проверка пароля
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Обновление пароля
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    // Выход из системы
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});