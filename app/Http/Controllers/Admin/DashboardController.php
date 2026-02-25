<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $revenue = Order::where('payment_status', 'paid')
            ->sum('total');

        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $popularProducts = OrderItem::select('product_id', 'product_name', DB::raw('sum(quantity) as total_quantity'))
            ->groupBy('product_id', 'product_name')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'totalProducts', 'totalUsers', 'totalCategories',
            'recentOrders', 'revenue', 'ordersByStatus', 'popularProducts'
        ));
    }
}