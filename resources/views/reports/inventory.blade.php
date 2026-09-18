@extends('layouts.app')
@section('title', 'Inventory Report')
@section('page-title', 'Inventory Report')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Inventory Report</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Product stock and valuation overview</p>
    </div>
    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-success">
        <i class="bi bi-download me-1"></i> Export CSV
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="bi bi-box" style="color:#3b82f6;"></i>
            </div>
            <div class="stat-value">{{ $summary['total_products'] }}</div>
            <div class="stat-label">Total Products</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="bi bi-currency-rupee" style="color:#22c55e;"></i>
            </div>
            <div class="stat-value">₹{{ number_format($summary['total_stock_value'], 0) }}</div>
            <div class="stat-label">Stock Value</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fffbeb;">
                <i class="bi bi-exclamation-triangle" style="color:#f59e0b;"></i>
            </div>
            <div class="stat-value">{{ $summary['low_stock'] }}</div>
            <div class="stat-label">Low Stock</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff1f2;">
                <i class="bi bi-x-circle" style="color:#ef4444;"></i>
            </div>
            <div class="stat-value">{{ $summary['out_of_stock'] }}</div>
            <div class="stat-label">Out of Stock</div>
        </div>
    </div>
</div>

<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row g-2">
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stock" class="form-select">
                        <option value="">All Stock</option>
                        <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    </select>
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('reports.inventory') }}" class="btn btn-light flex-fill">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h6><i class="bi bi-box me-2"></i>Product Inventory</h6>
    </div>
    <div class="table-responsive">
        @if($products->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Purchase</th>
                    <th>Selling</th>
                    <th>Stock</th>
                    <th>Min</th>
                    <th>Stock Value</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                @php $isLow = $product->stock_quantity <= $product->min_stock_level; @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:0.875rem;">{{ $product->name }}</div>
                        @if($isLow)
                        <span style="font-size:0.72rem;color:#ef4444;">
                            <i class="bi bi-exclamation-triangle me-1"></i>Low Stock
                        </span>
                        @endif
                    </td>
                    <td><code style="font-size:0.78rem;">{{ $product->sku }}</code></td>
                    <td style="font-size:0.82rem;">{{ $product->category->name ?? '—' }}</td>
                    <td style="font-size:0.82rem;">₹{{ number_format($product->purchase_price, 0) }}</td>
                    <td style="font-size:0.82rem;font-weight:600;">₹{{ number_format($product->selling_price, 0) }}</td>
                    <td>
                        <span class="badge badge-status {{ $isLow ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </td>
                    <td style="font-size:0.82rem;color:#94a3b8;">{{ $product->min_stock_level }}</td>
                    <td style="font-size:0.875rem;font-weight:600;">
                        ₹{{ number_format($product->stock_quantity * $product->purchase_price, 0) }}
                    </td>
                    <td>
                        <span class="badge badge-status {{ $product->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($products->hasPages())
        <div class="px-4 py-3 border-top">{{ $products->links() }}</div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-box" style="font-size:3rem;color:#e2e8f0;"></i>
            <h6 class="mt-3 text-muted">No products found</h6>
        </div>
        @endif
    </div>
</div>

@endsection