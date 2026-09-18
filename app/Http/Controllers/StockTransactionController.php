<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Http\Requests\StockTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = StockTransaction::with(['product', 'user'])->latest();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(15)->withQueryString();
        $products     = Product::orderBy('name')->get();

        return view('stock-transactions.index', compact('transactions', 'products'));
    }

    public function store(StockTransactionRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        // Validate stock_out doesn't go below 0
        if ($request->type === 'stock_out' && $product->stock_quantity < $request->quantity) {
            return back()->withErrors([
                'quantity' => "Insufficient stock. Available: {$product->stock_quantity} units."
            ])->withInput();
        }

        DB::transaction(function() use ($request, $product) {
            $before = $product->stock_quantity;

            // Calculate new stock
            $after = match($request->type) {
                'stock_in'    => $before + $request->quantity,
                'stock_out'   => $before - $request->quantity,
                'adjustment'  => $request->quantity, // Set exact quantity
            };

            // Create transaction record
            StockTransaction::create([
                'product_id'      => $product->id,
                'user_id'         => auth()->id(),
                'type'            => $request->type,
                'quantity'        => $request->quantity,
                'quantity_before' => $before,
                'quantity_after'  => $after,
                'notes'           => $request->notes,
            ]);

            // Update product stock
            $product->update(['stock_quantity' => $after]);
        });

        return back()->with('success', 'Stock updated successfully!');
    }
}