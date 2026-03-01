<?php

// Пространство имен для страницы редактирования товара
namespace App\Filament\Resources\ProductResource\Pages;

// Импорт необходимых классов
use App\Filament\Resources\ProductResource; // Класс ресурса товара
use Filament\Actions; // Пространство имен для действий Filament
use Filament\Resources\Pages\EditRecord; // Базовый класс для страницы редактирования записи

/**
 * Класс страницы редактирования товара
 */
class EditProduct extends EditRecord
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
            Actions\DeleteAction::make(), // Кнопка удаления товара
        ];
    }
}