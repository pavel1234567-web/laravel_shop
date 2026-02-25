<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller {

    public function index(Request $request) {
        $query = Product::with('category');
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }
        $products = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function create() {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'is_active'   => 'boolean',
        ]);
        $data['slug'] = Str::slug($data['name']) . '-' . time();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Товар добавлен!');
    }

    public function edit(Product $product) {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product) {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Товар обновлён!');
    }

    public function destroy(Product $product) {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Товар удалён!');
    }
}