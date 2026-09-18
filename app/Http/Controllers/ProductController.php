<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockTransaction;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('stock') && $request->stock === 'low') {
            $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
        }

        $products   = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        // Log initial stock as stock_in transaction
        if ($product->stock_quantity > 0) {
            StockTransaction::create([
                'product_id'      => $product->id,
                'user_id'         => auth()->id(),
                'type'            => 'stock_in',
                'quantity'        => $product->stock_quantity,
                'quantity_before' => 0,
                'quantity_after'  => $product->stock_quantity,
                'notes'           => 'Initial stock on product creation.',
            ]);
        }

        return redirect()->route('products.index')
                         ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load('category');
        $transactions = StockTransaction::where('product_id', $product->id)
                                        ->with('user')
                                        ->latest()
                                        ->paginate(10);
        return view('products.show', compact('product', 'transactions'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('products.index')
                         ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
                         ->with('success', 'Product deleted successfully!');
    }

        /**
     * AJAX: Get product price and stock
     */
    public function getPrice(Product $product)
    {
        return response()->json([
            'price'          => $product->selling_price,
            'stock'          => $product->stock_quantity,
            'name'           => $product->name,
        ]);
    }
}