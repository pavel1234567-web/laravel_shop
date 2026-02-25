<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model {
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'old_price', 'image', 'stock', 'is_active'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    // ↓ Автоматически возвращает полный URL картинки
    // ↓ ИСПРАВЛЕНО — используем asset() вместо Storage::exists()
    public function getImageUrlAttribute(): string {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/no-image.svg');
    }


    public function getDiscountPercentAttribute(): ?int {
        if ($this->old_price && $this->old_price > $this->price) {
            return round((1 - $this->price / $this->old_price) * 100);
        }
        return null;
    }
}