<?php

// Пространство имен для страницы редактирования категории
namespace App\Filament\Resources\CategoryResource\Pages;

// Импорт необходимых классов
use App\Filament\Resources\CategoryResource; // Класс ресурса категории
use Filament\Actions; // Пространство имен для действий Filament
use Filament\Resources\Pages\EditRecord; // Базовый класс для страницы редактирования записи

/**
 * Класс страницы редактирования категории
 */
class EditCategory extends EditRecord
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
            Actions\DeleteAction::make(), // Кнопка удаления категории
        ];
    }
}