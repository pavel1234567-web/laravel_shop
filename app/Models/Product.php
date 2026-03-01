<?php

// Пространство имен для моделей приложения
namespace App\Models;

// Импорт классов
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Класс модели товара
 * 
 * Связан с таблицей 'products' в базе данных
 */
class Product extends Model 
{
    /**
     * Атрибуты, которые можно массово присваивать
     * 
     * @var array
     */
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'old_price', 'image', 'stock', 'is_active'
    ];

    /**
     * Связь с моделью Category (обратная связь)
     * Товар принадлежит одной категории
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() 
    {
        return $this->belongsTo(Category::class); // Внешний ключ: category_id
    }

    /**
     * Аксессор для получения полного URL изображения
     * Автоматически возвращает полный URL картинки
     * 
     * @return string
     */
    public function getImageUrlAttribute(): string 
    {
        // Если изображение существует, возвращаем путь через asset()
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        // Если изображения нет, возвращаем заглушку
        return asset('images/no-image.svg');
    }

    /**
     * Аксессор для получения процента скидки
     * Вычисляется на основе старой и текущей цены
     * 
     * @return int|null
     */
    public function getDiscountPercentAttribute(): ?int 
    {
        // Если есть старая цена и она выше текущей
        if ($this->old_price && $this->old_price > $this->price) {
            // Вычисляем процент скидки
            return round((1 - $this->price / $this->old_price) * 100);
        }
        
        // Если скидки нет, возвращаем null
        return null;
    }
}