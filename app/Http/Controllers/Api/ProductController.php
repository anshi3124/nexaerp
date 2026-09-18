<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
                           ->when($request->search, fn($q) =>
                               $q->where('name', 'like', "%{$request->search}%")
                                 ->orWhere('sku',  'like', "%{$request->search}%"))
                           ->when($request->status, fn($q) =>
                               $q->where('status', $request->status))
                           ->when($request->low_stock, fn($q) =>
                               $q->whereColumn('stock_quantity', '<=', 'min_stock_level'))
                           ->latest()
                           ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data'    => $products,
        ]);
    }

    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'data'    => $product->load('category'),
        ]);
    }
}