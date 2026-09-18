<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use App\Http\Requests\InvoiceRequest;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $invoiceService) {}

    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'creator'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $invoices  = $query->paginate(10)->withQueryString();
        $customers = Customer::orderBy('name')->get();

        // Summary stats
        $summary = [
            'total'    => Invoice::sum('grand_total'),
            'paid'     => Invoice::where('status', 'paid')->sum('grand_total'),
            'pending'  => Invoice::whereIn('status', ['sent', 'partial', 'overdue'])->sum('remaining_amount'),
            'overdue'  => Invoice::where('status', 'overdue')->count(),
        ];

        return view('invoices.index', compact('invoices', 'customers', 'summary'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $products  = Product::where('status', 'active')->orderBy('name')->get();
        return view('invoices.create', compact('customers', 'products'));
    }

    public function store(InvoiceRequest $request)
    {
        $invoice = $this->invoiceService->createInvoice(
            $request->validated(),
            $request->input('items')
        );

        return redirect()->route('invoices.show', $invoice)
                         ->with('success', "Invoice {$invoice->invoice_number} created successfully!");
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'items.product', 'payments', 'creator']);
        $products = Product::where('status', 'active')->orderBy('name')->get();
        return view('invoices.show', compact('invoice', 'products'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                             ->with('error', 'Paid invoices cannot be edited.');
        }

        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $products  = Product::where('status', 'active')->orderBy('name')->get();
        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                             ->with('error', 'Paid invoices cannot be edited.');
        }

        // Restore stock from old items
        foreach ($invoice->items as $item) {
            $product = $item->product;
            $product->increment('stock_quantity', $item->quantity);
        }

        // Delete old items
        $invoice->items()->delete();

        // Recalculate totals
        $items  = $request->input('items');
        $totals = $this->invoiceService->calculateTotals(
            $items,
            $request->tax_percent ?? 0,
            $request->discount_percent ?? 0
        );

        $invoice->update(array_merge($request->validated(), [
            'subtotal'        => $totals['subtotal'],
            'tax_amount'      => $totals['tax_amount'],
            'discount_amount' => $totals['discount_amount'],
            'grand_total'     => $totals['grand_total'],
            'remaining_amount'=> $totals['grand_total'] - $invoice->paid_amount,
        ]));

        // Recreate items
        foreach ($items as $item) {
            $product   = Product::findOrFail($item['product_id']);
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $itemDisc  = $itemTotal * (($item['discount_percent'] ?? 0) / 100);

            $invoice->items()->create([
                'product_id'       => $product->id,
                'quantity'         => $item['quantity'],
                'unit_price'       => $item['unit_price'],
                'discount_percent' => $item['discount_percent'] ?? 0,
                'total'            => round($itemTotal - $itemDisc, 2),
            ]);

            // Deduct stock again
            $product->decrement('stock_quantity', $item['quantity']);
        }

        return redirect()->route('invoices.show', $invoice)
                         ->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.index')
                             ->with('error', 'Paid invoices cannot be deleted.');
        }

        // Restore stock
        foreach ($invoice->items as $item) {
            $item->product->increment('stock_quantity', $item->quantity);
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
                         ->with('success', 'Invoice deleted successfully!');
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate(['status' => 'required|in:draft,sent,paid,partial,overdue,cancelled']);
        $invoice->update(['status' => $request->status]);
        return back()->with('success', 'Invoice status updated!');
    }
}