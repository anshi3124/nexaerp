@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── Page Header ── --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">
            Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},
            {{ auth()->user()->name }}! 👋
        </h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">
            Here's what's happening with your business today.
        </p>
    </div>
    <div class="text-muted" style="font-size:0.8rem;">
        <i class="bi bi-calendar3 me-1"></i>
        {{ now()->format('l, d M Y') }}
    </div>
</div>

{{-- ── Stat Cards Row 1 ── --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="bi bi-people-fill" style="color:#3b82f6;"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
            <div class="stat-label">Total Customers</div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up-short"></i> Active CRM records
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;">
                <i class="bi bi-funnel-fill" style="color:#f59e0b;"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_leads']) }}</div>
            <div class="stat-label">Total Leads</div>
            <div class="stat-change up">
                <i class="bi bi-trophy"></i> {{ $stats['won_leads'] }} won
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="bi bi-box-seam-fill" style="color:#22c55e;"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
            <div class="stat-label">Total Products</div>
            @if($stats['low_stock'] > 0)
            <div class="stat-change down">
                <i class="bi bi-exclamation-triangle"></i> {{ $stats['low_stock'] }} low stock
            </div>
            @else
            <div class="stat-change up">
                <i class="bi bi-check-circle"></i> All stocked
            </div>
            @endif
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf4ff;">
                <i class="bi bi-receipt-cutoff" style="color:#a855f7;"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_invoices']) }}</div>
            <div class="stat-label">Total Invoices</div>
            <div class="stat-change {{ $stats['pending_payments'] > 0 ? 'down' : 'up' }}">
                <i class="bi bi-clock"></i> {{ $stats['pending_payments'] }} pending
            </div>
        </div>
    </div>

</div>

{{-- ── Stat Cards Row 2 ── --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff1f2;">
                <i class="bi bi-exclamation-circle-fill" style="color:#ef4444;"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['pending_payments']) }}</div>
            <div class="stat-label">Pending Payments</div>
            <div class="stat-change down">
                <i class="bi bi-arrow-right"></i> Requires action
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="bi bi-currency-rupee" style="color:#22c55e;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($stats['monthly_revenue'], 0) }}</div>
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-change up">
                <i class="bi bi-calendar"></i> {{ now()->format('M Y') }}
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed;">
                <i class="bi bi-archive-fill" style="color:#f97316;"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['low_stock']) }}</div>
            <div class="stat-label">Low Stock Items</div>
            <div class="stat-change {{ $stats['low_stock'] > 0 ? 'down' : 'up' }}">
                <i class="bi bi-{{ $stats['low_stock'] > 0 ? 'exclamation-triangle' : 'check-circle' }}"></i>
                {{ $stats['low_stock'] > 0 ? 'Reorder needed' : 'All good' }}
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="bi bi-trophy-fill" style="color:#3b82f6;"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['won_leads']) }}</div>
            <div class="stat-label">Won Leads</div>
            <div class="stat-change up">
                <i class="bi bi-graph-up-arrow"></i> Converted
            </div>
        </div>
    </div>

</div>

{{-- ── Charts Row ── --}}
<div class="row g-3 mb-4">

    {{-- Monthly Revenue Chart --}}
    <div class="col-md-8">
        <div class="content-card">
            <div class="card-header">
                <h6><i class="bi bi-bar-chart me-2 text-primary"></i>Monthly Revenue</h6>
                <span class="badge bg-light text-muted" style="font-size:0.72rem;">Last 6 Months</span>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Lead Status Chart --}}
    <div class="col-md-4">
        <div class="content-card">
            <div class="card-header">
                <h6><i class="bi bi-pie-chart me-2 text-warning"></i>Lead Pipeline</h6>
            </div>
            <div class="card-body">
                <canvas id="leadChart" height="200"></canvas>
                <div class="mt-3">
                    @foreach(['new'=>'#6366f1','contacted'=>'#3b82f6','qualified'=>'#f59e0b','proposal'=>'#8b5cf6','won'=>'#22c55e','lost'=>'#ef4444'] as $status => $color)
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size:0.75rem; color:#64748b;">
                            <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{$color}};margin-right:5px;"></span>
                            {{ ucfirst($status) }}
                        </span>
                        <span style="font-size:0.75rem; font-weight:600;">
                            {{ $leadStats[$status] ?? 0 }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Tables Row ── --}}
<div class="row g-3">

    {{-- Recent Invoices --}}
    <div class="col-md-7">
        <div class="content-card">
            <div class="card-header">
                <h6><i class="bi bi-receipt me-2 text-primary"></i>Recent Invoices</h6>
                <a href="#" class="btn btn-sm btn-light" style="font-size:0.78rem;">View All</a>
            </div>
            <div class="table-responsive">
                @if($recentInvoices->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentInvoices as $invoice)
                        <tr>
                            <td>
                                <span style="font-weight:600; font-size:0.82rem;">
                                    {{ $invoice->invoice_number }}
                                </span>
                                <br>
                                <span style="font-size:0.75rem; color:#94a3b8;">
                                    {{ $invoice->invoice_date->format('d M Y') }}
                                </span>
                            </td>
                            <td style="font-size:0.85rem;">{{ $invoice->customer->name }}</td>
                            <td style="font-weight:600; font-size:0.85rem;">
                                ₹{{ number_format($invoice->grand_total, 0) }}
                            </td>
                            <td>
                                @php
                                    $colors = [
                                        'draft'     => 'secondary',
                                        'sent'      => 'info',
                                        'paid'      => 'success',
                                        'partial'   => 'warning',
                                        'overdue'   => 'danger',
                                        'cancelled' => 'dark',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $colors[$invoice->status] ?? 'secondary' }}-subtle
                                             text-{{ $colors[$invoice->status] ?? 'secondary' }}
                                             badge-status">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-receipt" style="font-size:2rem; color:#e2e8f0;"></i>
                    <p class="text-muted mt-2 mb-0" style="font-size:0.85rem;">No invoices yet</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Low Stock Alert --}}
    <div class="col-md-5">
        <div class="content-card">
            <div class="card-header">
                <h6><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Low Stock Alert</h6>
                <a href="#" class="btn btn-sm btn-light" style="font-size:0.78rem;">View All</a>
            </div>
            <div class="table-responsive">
                @if($lowStockProducts->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Min</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockProducts as $product)
                        <tr>
                            <td>
                                <span style="font-size:0.85rem; font-weight:500;">{{ $product->name }}</span>
                                <br>
                                <span style="font-size:0.72rem; color:#94a3b8;">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger badge-status">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td style="font-size:0.85rem; color:#94a3b8;">
                                {{ $product->min_stock_level }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-check-circle" style="font-size:2rem; color:#22c55e;"></i>
                    <p class="text-muted mt-2 mb-0" style="font-size:0.85rem;">All products are well stocked!</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
// ── Revenue Chart ──
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
        datasets: [{
            label: 'Revenue (₹)',
            data: {!! json_encode($monthlyRevenue->pluck('amount')) !!},
            backgroundColor: 'rgba(102, 126, 234, 0.15)',
            borderColor: '#667eea',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => '₹' + ctx.parsed.y.toLocaleString('en-IN')
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f0f2f5' },
                ticks: {
                    font: { size: 11 },
                    callback: val => '₹' + val.toLocaleString('en-IN')
                }
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 11 } }
            }
        }
    }
});

// ── Lead Chart ──
const leadCtx = document.getElementById('leadChart').getContext('2d');
const leadData = {!! json_encode($leadStats) !!};
new Chart(leadCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(leadData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
        datasets: [{
            data: Object.values(leadData),
            backgroundColor: ['#6366f1','#3b82f6','#f59e0b','#8b5cf6','#22c55e','#ef4444'],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
@endpush