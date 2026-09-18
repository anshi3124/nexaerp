@extends('layouts.app')
@section('title', 'Customer Report')
@section('page-title', 'Customer Report')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Customer Report</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Customer activity and billing overview</p>
    </div>
    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-success">
        <i class="bi bi-download me-1"></i> Export CSV
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['Total Customers', $summary['total'], '#3b82f6', 'bi-people'],
        ['Active',          $summary['active'], '#22c55e', 'bi-check-circle'],
        ['Inactive',        $summary['inactive'], '#94a3b8', 'bi-x-circle'],
        ['With Invoices',   $summary['with_invoices'], '#a855f7', 'bi-receipt'],
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
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Search customers..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('reports.customers') }}" class="btn btn-light flex-fill">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h6><i class="bi bi-people me-2"></i>Customer Overview</h6>
    </div>
    <div class="table-responsive">
        @if($customers->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Invoices</th>
                    <th>Total Billed</th>
                    <th>Total Paid</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:0.875rem;">{{ $customer->name }}</div>
                        @if($customer->company)
                        <div style="font-size:0.75rem;color:#94a3b8;">{{ $customer->company }}</div>
                        @endif
                    </td>
                    <td style="font-size:0.82rem;">
                        {{ $customer->email }}<br>
                        <span style="color:#94a3b8;">{{ $customer->phone }}</span>
                    </td>
                    <td style="font-size:0.82rem;">
                        {{ collect([$customer->city, $customer->country])->filter()->implode(', ') ?: '—' }}
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary badge-status">
                            {{ $customer->invoices_count }}
                        </span>
                    </td>
                    <td style="font-weight:600;font-size:0.875rem;">
                        ₹{{ number_format($customer->invoices_sum_grand_total ?? 0, 0) }}
                    </td>
                    <td style="color:#22c55e;font-weight:500;font-size:0.875rem;">
                        ₹{{ number_format($customer->invoices_sum_paid_amount ?? 0, 0) }}
                    </td>
                    <td>
                        <span class="badge badge-status {{ $customer->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($customers->hasPages())
        <div class="px-4 py-3 border-top">{{ $customers->links() }}</div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-people" style="font-size:3rem;color:#e2e8f0;"></i>
            <h6 class="mt-3 text-muted">No customers found</h6>
        </div>
        @endif
    </div>
</div>

@endsection