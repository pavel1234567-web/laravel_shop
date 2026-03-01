<?php

// Пространство имен для страницы списка категорий
namespace App\Filament\Resources\CategoryResource\Pages;

// Импорт необходимых классов
use App\Filament\Resources\CategoryResource; // Класс ресурса категории
use Filament\Actions; // Пространство имен для действий Filament
use Filament\Resources\Pages\ListRecords; // Базовый класс для страницы списка записей

/**
 * Класс страницы со списком категорий
 */
class ListCategories extends ListRecords
{
    // Привязка к ресурсу категории
    protected static string $resource = CategoryResource::class;

    /**
     * Возвращает массив действий в заголовке страницы
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(), // Кнопка создания новой категории
        ];
    }
}