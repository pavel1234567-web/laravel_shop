<?php

// Пространство имен для провайдеров Filament
namespace App\Providers\Filament;

// Импорт middleware для HTTP запросов
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Класс провайдера панели администратора
 * Настраивает и регистрирует админ-панель Filament
 */
class AdminPanelProvider extends PanelProvider
{
    /**
     * Настройка панели администратора
     * 
     * @param Panel $panel
     * @return Panel
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            // Установка как панели по умолчанию
            ->default()
            
            // Уникальный идентификатор панели
            ->id('admin')
            
            // URL путь к панели (например, http://site.com/admin)
            ->path('admin')
            
            // Включение страницы входа
            ->login()
            
            // Настройка цветовой схемы
            ->colors([
                'primary' => Color::Amber, // Основной цвет - янтарный
            ])
            
            // Автоматическое обнаружение ресурсов в указанной директории
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            
            // Автоматическое обнаружение страниц в указанной директории
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            
            // Явная регистрация страниц
            ->pages([
                Pages\Dashboard::class, // Главная страница дашборда
            ])
            
            // Автоматическое обнаружение виджетов
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            
            // Явная регистрация виджетов
            ->widgets([
                Widgets\AccountWidget::class,       // Виджет информации об аккаунте
                Widgets\FilamentInfoWidget::class,  // Виджет информации о Filament
            ])
            
            // Middleware для всех запросов к панели
            ->middleware([
                EncryptCookies::class,              // Шифрование cookies
                AddQueuedCookiesToResponse::class,  // Добавление cookies в очередь
                StartSession::class,                 // Запуск сессии
                AuthenticateSession::class,          // Аутентификация сессии
                ShareErrorsFromSession::class,       // Передача ошибок из сессии
                VerifyCsrfToken::class,              // Проверка CSRF токена
                SubstituteBindings::class,           // Подстановка связанных моделей
                DisableBladeIconComponents::class,   // Отключение иконок Blade
                DispatchServingFilamentEvent::class, // Диспетчеризация события Serving Filament
            ])
            
            // Middleware для аутентификации
            ->authMiddleware([
                Authenticate::class, // Проверка аутентификации пользователя
            ]);
    }
}