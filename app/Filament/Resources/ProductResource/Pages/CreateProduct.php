<?php

// Пространство имен для страницы создания товара
namespace App\Filament\Resources\ProductResource\Pages;

// Импорт необходимых классов
use App\Filament\Resources\ProductResource; // Класс ресурса товара
use Filament\Actions; // Пространство имен для действий Filament
use Filament\Resources\Pages\CreateRecord; // Базовый класс для страницы создания записи

/**
 * Класс страницы создания нового товара
 */
class CreateProduct extends CreateRecord
{
    // Привязка к ресурсу товара
    protected static string $resource = ProductResource::class;
}