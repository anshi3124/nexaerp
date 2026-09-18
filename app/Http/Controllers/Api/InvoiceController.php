<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with('customer')
                           ->when($request->status, fn($q) =>
                               $q->where('status', $request->status))
                           ->when($request->customer_id, fn($q) =>
                               $q->where('customer_id', $request->customer_id))
                           ->latest()
                           ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data'    => $invoices,
        ]);
    }

    public function show(Invoice $invoice)
    {
        return response()->json([
            'success' => true,
            'data'    => $invoice->load(['customer', 'items.product', 'payments']),
        ]);
    }
}