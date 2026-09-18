@extends('layouts.app')

@section('title', 'Invoices')
@section('page-title', 'Invoices')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Invoices</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Manage your sales invoices</p>
    </div>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Create Invoice
    </a>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="bi bi-receipt-cutoff" style="color:#3b82f6;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($summary['total'], 0) }}</div>
            <div class="stat-label">Total Invoiced</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="bi bi-check-circle" style="color:#22c55e;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($summary['paid'], 0) }}</div>
            <div class="stat-label">Total Paid</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fffbeb;">
                <i class="bi bi-clock" style="color:#f59e0b;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($summary['pending'], 0) }}</div>
            <div class="stat-label">Pending Amount</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff1f2;">
                <i class="bi bi-exclamation-circle" style="color:#ef4444;"></i>
            </div>
            <div class="stat-value">{{ $summary['overdue'] }}</div>
            <div class="stat-label">Overdue Invoices</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('invoices.index') }}">
            <div class="row g-2">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Search invoice or customer..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="customer_id" class="form-select">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['draft','sent','paid','partial','overdue','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-light flex-fill">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="content-card">
    <div class="card-header">
        <h6><i class="bi bi-receipt me-2"></i>All Invoices
            <span class="badge bg-primary ms-2">{{ $invoices->total() }}</span>
        </h6>
    </div>
    <div class="table-responsive">
        @if($invoices->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr>
                    <td>
                        <a href="{{ route('invoices.show', $invoice) }}"
                           style="font-weight:600;color:#667eea;text-decoration:none;font-size:0.875rem;">
                            {{ $invoice->invoice_number }}
                        </a>
                    </td>
                    <td>
                        <div style="font-size:0.875rem;font-weight:500;">{{ $invoice->customer->name }}</div>
                        @if($invoice->customer->company)
                        <div style="font-size:0.75rem;color:#94a3b8;">{{ $invoice->customer->company }}</div>
                        @endif
                    </td>
                    <td style="font-size:0.82rem;">{{ $invoice->invoice_date->format('d M Y') }}</td>
                    <td style="font-size:0.82rem;">
                        @if($invoice->due_date)
                            <span class="{{ $invoice->is_overdue ? 'text-danger fw-600' : '' }}">
                                {{ $invoice->due_date->format('d M Y') }}
                            </span>
                        @else
                            —
                        @endif
                    </td>
                    <td style="font-weight:600;font-size:0.875rem;">
                        ₹{{ number_format($invoice->grand_total, 0) }}
                    </td>
                    <td style="font-size:0.875rem;color:#22c55e;font-weight:500;">
                        ₹{{ number_format($invoice->paid_amount, 0) }}
                    </td>
                    <td style="font-size:0.875rem;color:#ef4444;font-weight:500;">
                        ₹{{ number_format($invoice->remaining_amount, 0) }}
                    </td>
                    <td>
                        <span class="badge badge-status bg-{{ $invoice->status_color }}-subtle text-{{ $invoice->status_color }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('invoices.show', $invoice) }}"
                               class="btn btn-sm btn-light" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($invoice->status !== 'paid')
                            <a href="{{ route('invoices.edit', $invoice) }}"
                               class="btn btn-sm btn-light" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                                  onsubmit="return confirm('Delete invoice {{ $invoice->invoice_number }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
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
            <h6 class="mt-3 text-muted">No invoices found</h6>
            <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm mt-2">
                <i class="bi bi-plus-lg me-1"></i> Create Invoice
            </a>
        </div>
        @endif
    </div>
</div>

@endsection