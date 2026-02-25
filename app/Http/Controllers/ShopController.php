<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{

    public function index()
    {
        $categories = Category::withCount('products')->get();
        $featuredProducts = Product::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
        return view('shop.index', compact('categories', 'featuredProducts'));
    }

    public function catalog(Request $request)
    {
        $query = Product::with('category')->where('is_active', 1);

        // --- ПОИСК ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // --- ФИЛЬТР ПО КАТЕГОРИИ ---
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // --- ФИЛЬТР ПО ДИАПАЗОНУ ЦЕНЫ ---
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // --- СОРТИРОВКА ---
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };



        // --- ПАГИНАЦИЯ ---
        $products = $query->paginate(9)->withQueryString();

        $categories = Category::all();
        $priceRange = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        // ↓ AJAX — вернуть только partial
        if ($request->ajax()) {
            return response()->json([
                'html' => view('shop.partials.products', compact('products'))->render(),
                'total' => $products->total(),
            ]);
        }

        return view('shop.catalog', compact('products', 'categories', 'priceRange'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->where('is_active', 1)
            ->paginate(9)
            ->withQueryString(); 
        return view('shop.category', compact('category', 'products'));
    }

    public function product($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', 1)
            ->limit(4)->get();
        return view('shop.product', compact('product', 'related'));
    }
}