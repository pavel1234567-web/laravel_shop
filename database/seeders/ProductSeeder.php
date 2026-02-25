<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Смартфон Galaxy S23',
                'slug' => 'samsung-galaxy-s23',
                'description' => 'Флагманский смартфон с отличной камерой и производительностью',
                'short_description' => '8/256 ГБ, экран 6.1"',
                'price' => 79999.99,
                'old_price' => 89999.99,
                'quantity' => 15,
                'sku' => 'SM-S23-001',
                'category_id' => 1,
                'is_featured' => true,
            ],
            [
                'name' => 'Ноутбук ASUS ROG',
                'slug' => 'asus-rog-strix',
                'description' => 'Игровой ноутбук с мощной видеокартой',
                'short_description' => '16/512 ГБ, RTX 3060',
                'price' => 129999.99,
                'old_price' => 149999.99,
                'quantity' => 5,
                'sku' => 'AS-ROG-002',
                'category_id' => 1,
                'is_featured' => true,
            ],
            [
                'name' => 'Футболка хлопковая',
                'slug' => 'cotton-tshirt',
                'description' => 'Качественная хлопковая футболка',
                'short_description' => '100% хлопок, размеры S-XXL',
                'price' => 1999.99,
                'quantity' => 100,
                'sku' => 'CL-TSH-001',
                'category_id' => 2,
            ],
            [
                'name' => 'Джинсы классические',
                'slug' => 'classic-jeans',
                'description' => 'Классические джинсы из качественного денима',
                'short_description' => 'Синие, прямой крой',
                'price' => 3999.99,
                'old_price' => 4999.99,
                'quantity' => 50,
                'sku' => 'CL-JNS-002',
                'category_id' => 2,
            ],
            [
                'name' => 'Книга "Laravel для начинающих"',
                'slug' => 'laravel-book',
                'description' => 'Полное руководство по Laravel',
                'short_description' => '500 страниц, мягкая обложка',
                'price' => 2499.99,
                'quantity' => 30,
                'sku' => 'BK-LRV-001',
                'category_id' => 3,
                'is_featured' => true,
            ],
            [
                'name' => 'Мяч футбольный',
                'slug' => 'football-ball',
                'description' => 'Профессиональный футбольный мяч',
                'short_description' => 'Размер 5, камерный',
                'price' => 2999.99,
                'quantity' => 25,
                'sku' => 'SP-BAL-001',
                'category_id' => 4,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}