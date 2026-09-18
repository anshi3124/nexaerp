@extends('layouts.app')

@section('title', 'Payments')
@section('page-title', 'Payments')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Payments</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Track all payment transactions</p>
    </div>
    <a href="{{ route('invoices.index') }}" class="btn btn-light">
        <i class="bi bi-receipt me-1"></i> View Invoices
    </a>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="bi bi-currency-rupee" style="color:#22c55e;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($summary['total_received'], 0) }}</div>
            <div class="stat-label">Total Received</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="bi bi-calendar-check" style="color:#3b82f6;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($summary['this_month'], 0) }}</div>
            <div class="stat-label">This Month</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="bi bi-cash-stack" style="color:#22c55e;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($summary['cash'], 0) }}</div>
            <div class="stat-label">Cash Payments</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="bi bi-bank" style="color:#3b82f6;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($summary['bank'], 0) }}</div>
            <div class="stat-label">Bank Transfers</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('payments.index') }}">
            <div class="row g-2">
                <div class="col-md-4">
                    <select name="customer_id" class="form-select">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                        @endforeach
                    </select>
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
                    <a href="{{ route('payments.index') }}" class="btn btn-light flex-fill">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Payments Table --}}
<div class="content-card">
    <div class="card-header">
        <h6><i class="bi bi-credit-card me-2"></i>All Payments
            <span class="badge bg-primary ms-2">{{ $payments->total() }}</span>
        </h6>
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
                    <th>Recorded By</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td style="font-size:0.82rem;">
                        {{ $payment->payment_date->format('d M Y') }}
                    </td>
                    <td>
                        <a href="{{ route('invoices.show', $payment->invoice) }}"
                           style="font-weight:600;color:#667eea;text-decoration:none;font-size:0.82rem;">
                            {{ $payment->invoice->invoice_number }}
                        </a>
                    </td>
                    <td style="font-size:0.875rem;">{{ $payment->customer->name }}</td>
                    <td style="font-weight:700;color:#22c55e;font-size:0.875rem;">
                        ₹{{ number_format($payment->amount, 2) }}
                    </td>
                    <td>
                        <span class="badge badge-status bg-{{ $payment->method_color }}-subtle text-{{ $payment->method_color }}">
                            {{ $payment->method_label }}
                        </span>
                    </td>
                    <td style="font-size:0.8rem;color:#94a3b8;">
                        {{ $payment->reference_number ?? '—' }}
                    </td>
                    <td style="font-size:0.82rem;">{{ $payment->creator->name }}</td>
                    <td>
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST"
                              onsubmit="return confirm('Delete this payment?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light text-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
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
            <p class="text-muted" style="font-size:0.85rem;">
                Record payments from individual invoice pages.
            </p>
        </div>
        @endif
    </div>
</div>

@endsection