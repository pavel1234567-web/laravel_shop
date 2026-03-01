<?php

// Конфигурационный файл приложения Laravel
// Используется для начальной настройки и загрузки приложения

// Импорт классов для конфигурации приложения
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/**
 * Создание и настройка экземпляра приложения Laravel
 * 
 * 1. Конфигурирование приложения с указанием базового пути
 *    dirname(__DIR__) - поднимается на уровень выше (корень проекта)
 */
return Application::configure(basePath: dirname(__DIR__))
    
    /**
     * Настройка маршрутизации
     * 
     * web: __DIR__.'/../routes/web.php' - файл с web-маршрутами
     * commands: __DIR__.'/../routes/console.php' - файл с консольными командами
     * health: '/up' - эндпоинт для проверки работоспособности
     */
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    
    /**
     * Настройка middleware (прослойки)
     * 
     * Здесь можно регистрировать глобальные middleware
     * или группы middleware
     */
    ->withMiddleware(function (Middleware $middleware): void {
        // Место для настройки middleware
        // Пока пусто
    })
    
    /**
     * Настройка обработчика исключений
     * 
     * Здесь можно настроить кастомную обработку ошибок
     */
    ->withExceptions(function (Exceptions $exceptions): void {
        // Место для настройки обработки исключений
        // Пока пусто
    })
    
    /**
     * Создание экземпляра приложения
     * 
     * Возвращает настроенный экземпляр Application
     */
    ->create();