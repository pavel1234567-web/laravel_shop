<?php

// Пространство имен для моделей приложения
namespace App\Models;

// Импорт базового класса модели Eloquent
use Illuminate\Database\Eloquent\Model;

/**
 * Класс модели категории товаров
 * 
 * Связан с таблицей 'categories' в базе данных
 */
class Category extends Model 
{
    /**
     * Атрибуты, которые можно массово присваивать
     * 
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description', 'image'];

    /**
     * Связь с моделью Product (один ко многим)
     * Категория имеет много товаров
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products() 
    {
        return $this->hasMany(Product::class); // Внешний ключ: category_id
    }
}