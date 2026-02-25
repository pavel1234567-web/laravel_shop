<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $order = Order::create([
            'order_number' => 'ORD-' . date('Ymd') . '-001',
            'customer_name' => 'Иван Петров',
            'customer_email' => 'ivan@example.com',
            'customer_phone' => '+7 (999) 123-45-67',
            'customer_address' => 'г. Москва, ул. Ленина, д. 1, кв. 1',
            'subtotal' => 83999.98,
            'tax' => 0,
            'shipping' => 500,
            'total' => 84499.98,
            'status' => 'delivered',
            'payment_status' => 'paid',
            'payment_method' => 'card',
        ]);

        $product1 = Product::find(1);
        $product2 = Product::find(2);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
            'product_name' => $product1->name,
            'product_sku' => $product1->sku,
            'quantity' => 1,
            'price' => $product1->price,
            'total' => $product1->price,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
            'product_name' => $product2->name,
            'product_sku' => $product2->sku,
            'quantity' => 1,
            'price' => $product2->price,
            'total' => $product2->price,
        ]);
    }
}