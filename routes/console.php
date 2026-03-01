<?php

/**
 * Файл консольных команд Artisan
 * 
 * Здесь регистрируются пользовательские команды
 * для выполнения в консоли через php artisan
 */

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/**
 * Регистрация команды 'inspire'
 * 
 * Команда: php artisan inspire
 * Назначение: Отображает вдохновляющую цитату
 */
Artisan::command('inspire', function () {
    // Вывод цитаты в консоль
    // Inspiring::quote() возвращает случайную вдохновляющую цитату
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote'); // Описание команды