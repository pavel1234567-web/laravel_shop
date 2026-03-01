<?php

// Пространство имен для страницы создания категории
namespace App\Filament\Resources\CategoryResource\Pages;

// Импорт необходимых классов
use App\Filament\Resources\CategoryResource; // Класс ресурса категории
use Filament\Actions; // Пространство имен для действий Filament
use Filament\Resources\Pages\CreateRecord; // Базовый класс для страницы создания записи

/**
 * Класс страницы создания новой категории
 */
class CreateCategory extends CreateRecord
{
    // Привязка к ресурсу категории
    protected static string $resource = CategoryResource::class;
}