<?php

// Пространство имен для компонентов представлений
namespace App\View\Components;

// Импорт базового класса компонента
use Illuminate\View\Component;
// Импорт класса представления
use Illuminate\View\View;

/**
 * Класс компонента макета приложения
 * 
 * Представляет главный шаблон (layout) приложения
 * Используется для обертки содержимого страниц в единый макет
 */
class AppLayout extends Component
{
    /**
     * Получение представления / содержимого компонента
     * 
     * Этот метод вызывается при рендеринге компонента
     * Возвращает view, который будет использован как шаблон
     * 
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        // Возвращает представление 'layouts.app'
        // Файл должен находиться по пути: resources/views/layouts/app.blade.php
        return view('layouts.app');
    }
}