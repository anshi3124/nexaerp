@extends('layouts.app')

@section('title', 'Stock Transactions')
@section('page-title', 'Stock Transactions')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Stock Transactions</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">
            Complete inventory movement history
        </p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-light">
        <i class="bi bi-box me-1"></i> Back to Products
    </a>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="bi bi-box-arrow-in-down" style="color:#22c55e;"></i>
            </div>
            <div class="stat-value text-success">
                {{ $transactions->where('type', 'stock_in')->sum('quantity') }}
            </div>
            <div class="stat-label">Total Stock In</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff1f2;">
                <i class="bi bi-box-arrow-up" style="color:#ef4444;"></i>
            </div>
            <div class="stat-value text-danger">
                {{ $transactions->where('type', 'stock_out')->sum('quantity') }}
            </div>
            <div class="stat-label">Total Stock Out</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fffbeb;">
                <i class="bi bi-sliders" style="color:#f59e0b;"></i>
            </div>
            <div class="stat-value text-warning">
                {{ $transactions->where('type', 'adjustment')->count() }}
            </div>
            <div class="stat-label">Adjustments</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('stock-transactions.index') }}">
            <div class="row g-2">
                <div class="col-md-4">
                    <select name="product_id" class="form-select">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ $product->sku }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="stock_in"   {{ request('type') == 'stock_in'   ? 'selected' : '' }}>
                            Stock In
                        </option>
                        <option value="stock_out"  {{ request('type') == 'stock_out'  ? 'selected' : '' }}>
                            Stock Out
                        </option>
                        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>
                            Adjustment
                        </option>
                    </select>
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('stock-transactions.index') }}" class="btn btn-light flex-fill">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Transactions Table --}}
<div class="content-card">
    <div class="card-header">
        <h6>
            <i class="bi bi-arrow-left-right me-2"></i>
            All Transactions
            <span class="badge bg-primary ms-2">{{ $transactions->total() }}</span>
        </h6>
    </div>

    <div class="table-responsive">
        @if($transactions->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Before</th>
                    <th>After</th>
                    <th>Done By</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $txn)
                <tr>
                    <td>
                        <div style="font-size:0.82rem;font-weight:500;">
                            {{ $txn->created_at->format('d M Y') }}
                        </div>
                        <div style="font-size:0.75rem;color:#94a3b8;">
                            {{ $txn->created_at->format('h:i A') }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:0.875rem;">
                            {{ $txn->product->name }}
                        </div>
                        <code style="font-size:0.72rem;background:#f0f2f5;padding:1px 6px;border-radius:4px;">
                            {{ $txn->product->sku }}
                        </code>
                    </td>
                    <td>
                        <span class="badge badge-status
                            bg-{{ $txn->type_color }}-subtle
                            text-{{ $txn->type_color }}">
                            @if($txn->type === 'stock_in')
                                <i class="bi bi-arrow-down-circle me-1"></i>
                            @elseif($txn->type === 'stock_out')
                                <i class="bi bi-arrow-up-circle me-1"></i>
                            @else
                                <i class="bi bi-sliders me-1"></i>
                            @endif
                            {{ $txn->type_label }}
                        </span>
                    </td>
                    <td>
                        <span style="font-weight:700;font-size:1rem;"
                              class="text-{{ $txn->type === 'stock_in' ? 'success' : ($txn->type === 'stock_out' ? 'danger' : 'warning') }}">
                            {{ $txn->type === 'stock_in' ? '+' : ($txn->type === 'stock_out' ? '-' : '=') }}{{ $txn->quantity }}
                        </span>
                    </td>
                    <td>
                        <span style="font-size:0.875rem;color:#64748b;">
                            {{ $txn->quantity_before }}
                        </span>
                    </td>
                    <td>
                        <span style="font-size:0.875rem;font-weight:600;">
                            {{ $txn->quantity_after }}
                        </span>
                    </td>
                    <td>
                        <div style="font-size:0.82rem;">{{ $txn->user->name }}</div>
                    </td>
                    <td>
                        <span style="font-size:0.8rem;color:#94a3b8;">
                            {{ $txn->notes ?? '—' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $transactions->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-5">
            <i class="bi bi-arrow-left-right" style="font-size:3rem;color:#e2e8f0;"></i>
            <h6 class="mt-3 text-muted">No transactions found</h6>
            <p class="text-muted" style="font-size:0.85rem;">
                Go to a product and add stock transactions.
            </p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-box me-1"></i> View Products
            </a>
        </div>
        @endif
    </div>
</div>

@endsection