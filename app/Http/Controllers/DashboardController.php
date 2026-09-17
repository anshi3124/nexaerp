<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat Cards ──
        $stats = [
            'total_customers' => Customer::count(),
            'total_leads'     => Lead::count(),
            'total_products'  => Product::count(),
            'total_invoices'  => Invoice::count(),
            'pending_payments'=> Invoice::whereIn('status', ['sent', 'partial', 'overdue'])->count(),
            'monthly_revenue' => Payment::whereMonth('payment_date', now()->month)
                                        ->whereYear('payment_date', now()->year)
                                        ->sum('amount'),
            'low_stock'       => Product::whereColumn('stock_quantity', '<=', 'min_stock_level')->count(),
            'won_leads'       => Lead::where('status', 'won')->count(),
        ];

        // ── Monthly Revenue Chart (last 6 months) ──
        $monthlyRevenue = collect(range(5, 0))->map(function($i) {
            $date = now()->subMonths($i);
            return [
                'month'  => $date->format('M Y'),
                'amount' => Payment::whereMonth('payment_date', $date->month)
                                   ->whereYear('payment_date', $date->year)
                                   ->sum('amount'),
            ];
        });

        // ── Lead Status Chart ──
        $leadStats = Lead::select('status', DB::raw('count(*) as count'))
                         ->groupBy('status')
                         ->pluck('count', 'status');

        // ── Low Stock Products ──
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'min_stock_level')
                                   ->where('status', 'active')
                                   ->with('category')
                                   ->orderBy('stock_quantity')
                                   ->take(5)
                                   ->get();

        // ── Recent Invoices ──
        $recentInvoices = Invoice::with('customer')
                                 ->latest()
                                 ->take(5)
                                 ->get();

        return view('dashboard.index', compact(
            'stats',
            'monthlyRevenue',
            'leadStats',
            'lowStockProducts',
            'recentInvoices'
        ));
    }
}