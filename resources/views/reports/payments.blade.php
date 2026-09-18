@extends('layouts.app')
@section('title', 'Payment Report')
@section('page-title', 'Payment Report')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Payment Report</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Payment collection analysis</p>
    </div>
    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-success">
        <i class="bi bi-download me-1"></i> Export CSV
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['Total Received', '₹'.number_format($summary['total'],0), '#22c55e', 'bi-currency-rupee'],
        ['This Month',     '₹'.number_format($summary['this_month'],0), '#3b82f6', 'bi-calendar-check'],
        ['Cash',           '₹'.number_format($summary['cash'],0), '#f59e0b', 'bi-cash-stack'],
        ['Bank Transfer',  '₹'.number_format($summary['bank'],0), '#a855f7', 'bi-bank'],
    ] as [$label, $val, $color, $icon])
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $color }}20;">
                <i class="bi {{ $icon }}" style="color:{{ $color }};"></i>
            </div>
            <div class="stat-value">{{ $val }}</div>
            <div class="stat-label">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row g-2">
                <div class="col-md-2">
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-3">
                    <select name="payment_method" class="form-select">
                        <option value="">All Methods</option>
                        <option value="cash"          {{ request('payment_method') == 'cash'          ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="upi"           {{ request('payment_method') == 'upi'           ? 'selected' : '' }}>UPI</option>
                        <option value="card"          {{ request('payment_method') == 'card'          ? 'selected' : '' }}>Card</option>
                    </select>
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('reports.payments') }}" class="btn btn-light flex-fill">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h6><i class="bi bi-credit-card me-2"></i>Payment Transactions</h6>
    </div>
    <div class="table-responsive">
        @if($payments->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Reference</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td style="font-size:0.82rem;">{{ $payment->payment_date->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('invoices.show', $payment->invoice) }}"
                           style="color:#667eea;font-weight:600;font-size:0.82rem;text-decoration:none;">
                            {{ $payment->invoice->invoice_number }}
                        </a>
                    </td>
                    <td style="font-size:0.875rem;">{{ $payment->customer->name }}</td>
                    <td style="font-weight:700;color:#22c55e;">₹{{ number_format($payment->amount, 2) }}</td>
                    <td>
                        <span class="badge badge-status bg-{{ $payment->method_color }}-subtle text-{{ $payment->method_color }}">
                            {{ $payment->method_label }}
                        </span>
                    </td>
                    <td style="font-size:0.8rem;color:#94a3b8;">{{ $payment->reference_number ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($payments->hasPages())
        <div class="px-4 py-3 border-top">{{ $payments->links() }}</div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-credit-card" style="font-size:3rem;color:#e2e8f0;"></i>
            <h6 class="mt-3 text-muted">No payments found</h6>
        </div>
        @endif
    </div>
</div>

@endsection