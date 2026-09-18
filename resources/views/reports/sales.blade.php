@extends('layouts.app')
@section('title', 'Sales Report')
@section('page-title', 'Sales Report')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Sales Report</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Invoice and revenue analysis</p>
    </div>
    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-success">
        <i class="bi bi-download me-1"></i> Export CSV
    </a>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="bi bi-receipt" style="color:#3b82f6;"></i>
            </div>
            <div class="stat-value">{{ number_format($summary['total_invoices']) }}</div>
            <div class="stat-label">Total Invoices</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf4ff;">
                <i class="bi bi-currency-rupee" style="color:#a855f7;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($summary['total_amount'], 0) }}</div>
            <div class="stat-label">Total Billed</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="bi bi-check-circle" style="color:#22c55e;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($summary['total_paid'], 0) }}</div>
            <div class="stat-label">Total Collected</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff1f2;">
                <i class="bi bi-clock" style="color:#ef4444;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($summary['total_pending'], 0) }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row g-2">
                <div class="col-md-2">
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}" placeholder="From">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}" placeholder="To">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['draft','sent','paid','partial','overdue','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="customer_id" class="form-select">
                        <option value="">All Customers</option>
                        @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('reports.sales') }}" class="btn btn-light flex-fill">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="content-card">
    <div class="card-header">
        <h6><i class="bi bi-table me-2"></i>Invoice Details</h6>
    </div>
    <div class="table-responsive">
        @if($invoices->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Grand Total</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr>
                    <td>
                        <a href="{{ route('invoices.show', $invoice) }}"
                           style="color:#667eea;font-weight:600;font-size:0.875rem;text-decoration:none;">
                            {{ $invoice->invoice_number }}
                        </a>
                    </td>
                    <td style="font-size:0.875rem;">{{ $invoice->customer->name }}</td>
                    <td style="font-size:0.82rem;">{{ $invoice->invoice_date->format('d M Y') }}</td>
                    <td style="font-weight:600;">₹{{ number_format($invoice->grand_total, 0) }}</td>
                    <td style="color:#22c55e;font-weight:500;">₹{{ number_format($invoice->paid_amount, 0) }}</td>
                    <td style="color:#ef4444;">₹{{ number_format($invoice->remaining_amount, 0) }}</td>
                    <td>
                        @php $colors = ['draft'=>'secondary','sent'=>'info','paid'=>'success','partial'=>'warning','overdue'=>'danger','cancelled'=>'dark']; @endphp
                        <span class="badge badge-status bg-{{ $colors[$invoice->status] ?? 'secondary' }}-subtle text-{{ $colors[$invoice->status] ?? 'secondary' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($invoices->hasPages())
        <div class="px-4 py-3 border-top">{{ $invoices->links() }}</div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-receipt" style="font-size:3rem;color:#e2e8f0;"></i>
            <h6 class="mt-3 text-muted">No invoices found for selected filters</h6>
        </div>
        @endif
    </div>
</div>

@endsection