<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ── Sales Report ──────────────────────────────────────────
    public function sales(Request $request)
    {
        $query = Invoice::with('customer')->latest();

        if ($request->filled('from')) {
            $query->whereDate('invoice_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('invoice_date', '<=', $request->to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $invoices = $query->paginate(15)->withQueryString();

        $summary = [
            'total_invoices'  => $query->toBase()->getCountForPagination(),
            'total_amount'    => Invoice::when($request->from, fn($q) => $q->whereDate('invoice_date', '>=', $request->from))
                                        ->when($request->to,   fn($q) => $q->whereDate('invoice_date', '<=', $request->to))
                                        ->sum('grand_total'),
            'total_paid'      => Invoice::when($request->from, fn($q) => $q->whereDate('invoice_date', '>=', $request->from))
                                        ->when($request->to,   fn($q) => $q->whereDate('invoice_date', '<=', $request->to))
                                        ->sum('paid_amount'),
            'total_pending'   => Invoice::when($request->from, fn($q) => $q->whereDate('invoice_date', '>=', $request->from))
                                        ->when($request->to,   fn($q) => $q->whereDate('invoice_date', '<=', $request->to))
                                        ->sum('remaining_amount'),
        ];

        $customers = Customer::orderBy('name')->get();

        // CSV Export
        if ($request->export === 'csv') {
            return $this->exportCsv('sales_report', [
                'Invoice #', 'Customer', 'Date', 'Due Date', 'Status',
                'Subtotal', 'Tax', 'Discount', 'Grand Total', 'Paid', 'Remaining'
            ], $query->get()->map(fn($i) => [
                $i->invoice_number,
                $i->customer->name,
                $i->invoice_date->format('d M Y'),
                $i->due_date?->format('d M Y') ?? '',
                ucfirst($i->status),
                $i->subtotal,
                $i->tax_amount,
                $i->discount_amount,
                $i->grand_total,
                $i->paid_amount,
                $i->remaining_amount,
            ]));
        }

        return view('reports.sales', compact('invoices', 'summary', 'customers'));
    }

    // ── Customer Report ───────────────────────────────────────
    public function customers(Request $request)
    {
        $query = Customer::withCount('invoices')
                         ->withSum('invoices', 'grand_total')
                         ->withSum('invoices', 'paid_amount')
                         ->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->paginate(15)->withQueryString();

        $summary = [
            'total'    => Customer::count(),
            'active'   => Customer::where('status', 'active')->count(),
            'inactive' => Customer::where('status', 'inactive')->count(),
            'with_invoices' => Customer::has('invoices')->count(),
        ];

        if ($request->export === 'csv') {
            return $this->exportCsv('customer_report', [
                'Name', 'Company', 'Email', 'Phone', 'City', 'Status',
                'Total Invoices', 'Total Billed', 'Total Paid'
            ], $query->get()->map(fn($c) => [
                $c->name, $c->company, $c->email, $c->phone, $c->city,
                ucfirst($c->status),
                $c->invoices_count,
                $c->invoices_sum_grand_total ?? 0,
                $c->invoices_sum_paid_amount ?? 0,
            ]));
        }

        return view('reports.customers', compact('customers', 'summary'));
    }

    // ── Product/Inventory Report ──────────────────────────────
    public function inventory(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('stock') && $request->stock === 'low') {
            $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
        }

        $products = $query->paginate(15)->withQueryString();

        $summary = [
            'total_products'    => Product::count(),
            'total_stock_value' => Product::selectRaw('SUM(stock_quantity * purchase_price) as val')->value('val') ?? 0,
            'low_stock'         => Product::whereColumn('stock_quantity', '<=', 'min_stock_level')->count(),
            'out_of_stock'      => Product::where('stock_quantity', 0)->count(),
        ];

        $categories = \App\Models\Category::orderBy('name')->get();

        if ($request->export === 'csv') {
            return $this->exportCsv('inventory_report', [
                'Name', 'SKU', 'Category', 'Purchase Price', 'Selling Price',
                'Stock', 'Min Stock', 'Status', 'Stock Value'
            ], $query->get()->map(fn($p) => [
                $p->name, $p->sku,
                $p->category->name ?? 'Uncategorized',
                $p->purchase_price, $p->selling_price,
                $p->stock_quantity, $p->min_stock_level,
                ucfirst($p->status),
                $p->stock_quantity * $p->purchase_price,
            ]));
        }

        return view('reports.inventory', compact('products', 'summary', 'categories'));
    }

    // ── Payment Report ────────────────────────────────────────
    public function payments(Request $request)
    {
        $query = Payment::with(['invoice', 'customer'])->latest('payment_date');

        if ($request->filled('from')) {
            $query->whereDate('payment_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('payment_date', '<=', $request->to);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->paginate(15)->withQueryString();

        $summary = [
            'total'         => Payment::sum('amount'),
            'this_month'    => Payment::whereMonth('payment_date', now()->month)->sum('amount'),
            'cash'          => Payment::where('payment_method', 'cash')->sum('amount'),
            'bank'          => Payment::where('payment_method', 'bank_transfer')->sum('amount'),
            'upi'           => Payment::where('payment_method', 'upi')->sum('amount'),
            'card'          => Payment::where('payment_method', 'card')->sum('amount'),
        ];

        if ($request->export === 'csv') {
            return $this->exportCsv('payment_report', [
                'Date', 'Invoice #', 'Customer', 'Amount', 'Method', 'Reference'
            ], $query->get()->map(fn($p) => [
                $p->payment_date->format('d M Y'),
                $p->invoice->invoice_number,
                $p->customer->name,
                $p->amount,
                ucfirst(str_replace('_', ' ', $p->payment_method)),
                $p->reference_number ?? '',
            ]));
        }

        return view('reports.payments', compact('payments', 'summary'));
    }

    // ── Lead Report ───────────────────────────────────────────
    public function leads(Request $request)
    {
        $query = Lead::with(['assignedTo'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $leads = $query->paginate(15)->withQueryString();

        $summary = [
            'total'     => Lead::count(),
            'won'       => Lead::where('status', 'won')->count(),
            'lost'      => Lead::where('status', 'lost')->count(),
            'pipeline'  => Lead::whereNotIn('status', ['won', 'lost'])->sum('expected_value'),
        ];

        if ($request->export === 'csv') {
            return $this->exportCsv('lead_report', [
                'Name', 'Company', 'Email', 'Source', 'Status',
                'Expected Value', 'Assigned To', 'Follow-up Date'
            ], $query->get()->map(fn($l) => [
                $l->name, $l->company, $l->email, $l->source,
                ucfirst($l->status),
                $l->expected_value ?? 0,
                $l->assignedTo->name ?? '',
                $l->follow_up_date?->format('d M Y') ?? '',
            ]));
        }

        return view('reports.leads', compact('leads', 'summary'));
    }

    // ── CSV Export Helper ─────────────────────────────────────
    private function exportCsv(string $filename, array $headers, $rows)
    {
        $callback = function() use ($headers, $rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}_" . now()->format('Y-m-d') . ".csv",
        ]);
    }
}