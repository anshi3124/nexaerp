@extends('layouts.app')

@section('title', $product->name)
@section('page-title', 'Product Detail')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">{{ $product->name }}</h4>
        <code style="font-size:0.85rem;">{{ $product->sku }}</code>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

@php $isLowStock = $product->stock_quantity <= $product->min_stock_level; @endphp

@if($isLowStock)
<div class="alert alert-warning mb-4">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Low Stock Alert!</strong> Current stock ({{ $product->stock_quantity }}) is at or below minimum level ({{ $product->min_stock_level }}).
</div>
@endif

<div class="row g-4">

    {{-- Product Info --}}
    <div class="col-md-4">
        <div class="content-card mb-3">
            <div class="card-body">
                <div class="text-center py-3">
                    <div style="width:80px;height:80px;border-radius:16px;background:#eff6ff;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:2rem;">
                        📦
                    </div>
                    <h5 class="fw-bold mb-1">{{ $product->name }}</h5>
                    <code style="font-size:0.8rem;">{{ $product->sku }}</code>
                    <div class="mt-2">
                        <span class="badge badge-status {{ $product->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card mb-3">
            <div class="card-header"><h6>Pricing</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;">Purchase Price</div>
                    <div style="font-size:1.1rem;font-weight:600;">₹{{ number_format($product->purchase_price, 2) }}</div>
                </div>
                <div class="mb-3">
                    <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;">Selling Price</div>
                    <div style="font-size:1.1rem;font-weight:600;">₹{{ number_format($product->selling_price, 2) }}</div>
                </div>
                <div class="pt-2 border-top">
                    <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;">Profit Per Unit</div>
                    <div style="font-size:1.3rem;font-weight:700;color:#22c55e;">
                        ₹{{ number_format($product->selling_price - $product->purchase_price, 2) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header"><h6>Stock Info</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;">Current Stock</div>
                    <div style="font-size:1.5rem;font-weight:700;color:{{ $isLowStock ? '#ef4444' : '#22c55e' }};">
                        {{ $product->stock_quantity }} units
                    </div>
                </div>
                <div class="mb-3">
                    <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;">Min Stock Level</div>
                    <div style="font-size:1rem;font-weight:600;">{{ $product->min_stock_level }} units</div>
                </div>
                <div>
                    <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;">Category</div>
                    <div style="font-size:0.875rem;">{{ $product->category->name ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-md-8">

        {{-- Stock Transaction Form --}}
        <div class="content-card mb-4">
            <div class="card-header">
                <h6><i class="bi bi-plus-circle me-2"></i>Add Stock Transaction</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('stock-transactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Transaction Type</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="stock_in">📦 Stock In</option>
                                <option value="stock_out">📤 Stock Out</option>
                                <option value="adjustment">⚙️ Adjustment</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" min="1"
                                   class="form-control form-control-sm @error('quantity') is-invalid @enderror"
                                   placeholder="Enter quantity">
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Notes</label>
                            <input type="text" name="notes"
                                   class="form-control form-control-sm"
                                   placeholder="Reason for transaction...">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Add Transaction
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Transaction History --}}
        <div class="content-card">
            <div class="card-header">
                <h6><i class="bi bi-clock-history me-2"></i>Stock Transaction History</h6>
                <span class="badge bg-light text-muted">{{ $transactions->total() }}</span>
            </div>
            <div class="table-responsive">
                @if($transactions->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>By</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $txn)
                        <tr>
                            <td style="font-size:0.8rem;color:#94a3b8;">
                                {{ $txn->created_at->format('d M Y') }}<br>
                                {{ $txn->created_at->format('h:i A') }}
                            </td>
                            <td>
                                <span class="badge badge-status bg-{{ $txn->type_color }}-subtle text-{{ $txn->type_color }}">
                                    {{ $txn->type_label }}
                                </span>
                            </td>
                            <td style="font-weight:600;font-size:0.875rem;">
                                <span class="text-{{ $txn->type === 'stock_in' ? 'success' : ($txn->type === 'stock_out' ? 'danger' : 'warning') }}">
                                    {{ $txn->type === 'stock_in' ? '+' : ($txn->type === 'stock_out' ? '-' : '=') }}{{ $txn->quantity }}
                                </span>
                            </td>
                            <td style="font-size:0.85rem;">{{ $txn->quantity_before }}</td>
                            <td style="font-size:0.85rem;font-weight:600;">{{ $txn->quantity_after }}</td>
                            <td style="font-size:0.82rem;">{{ $txn->user->name }}</td>
                            <td style="font-size:0.8rem;color:#94a3b8;">{{ $txn->notes ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($transactions->hasPages())
                <div class="px-4 py-3 border-top">{{ $transactions->links() }}</div>
                @endif
                @else
                <div class="text-center py-4">
                    <i class="bi bi-clock-history" style="font-size:2rem;color:#e2e8f0;"></i>
                    <p class="text-muted mt-2 mb-0" style="font-size:0.85rem;">No transactions yet.</p>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection