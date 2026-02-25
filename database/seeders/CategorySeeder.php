<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder {
    public function run(): void {
        DB::statement("
            INSERT INTO categories (name, slug, description, created_at, updated_at) VALUES
            ('Электроника', 'elektronika', 'Смартфоны, ноутбуки, планшеты', NOW(), NOW()),
            ('Одежда', 'odezhda', 'Мужская и женская одежда', NOW(), NOW()),
            ('Спорт', 'sport', 'Спортивные товары и инвентарь', NOW(), NOW()),
            ('Книги', 'knigi', 'Художественная и учебная литература', NOW(), NOW()),
            ('Дом и сад', 'dom-i-sad', 'Товары для дома и сада', NOW(), NOW())
        ");
    }
}