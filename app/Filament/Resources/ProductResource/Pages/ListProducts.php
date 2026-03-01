<?php

// Пространство имен для страницы списка товаров
namespace App\Filament\Resources\ProductResource\Pages;

// Импорт необходимых классов
use App\Filament\Resources\ProductResource; // Класс ресурса товара
use Filament\Actions; // Пространство имен для действий Filament
use Filament\Resources\Pages\ListRecords; // Базовый класс для страницы списка записей

/**
 * Класс страницы со списком товаров
 */
class ListProducts extends ListRecords
{
    // Привязка к ресурсу товара
    protected static string $resource = ProductResource::class;

    /**
     * Возвращает массив действий в заголовке страницы
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(), // Кнопка создания нового товара
        ];
    }
}