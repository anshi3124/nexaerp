@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Products</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Manage your product catalog and inventory</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Product
    </a>
</div>

{{-- Filters --}}
<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}">
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Search by name or SKU..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
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
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-light flex-fill">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Products Table --}}
<div class="content-card">
    <div class="card-header">
        <h6>
            <i class="bi bi-box me-2"></i>All Products
            <span class="badge bg-primary ms-2">{{ $products->total() }}</span>
        </h6>
        <a href="{{ route('stock-transactions.index') }}" class="btn btn-sm btn-light">
            <i class="bi bi-arrow-left-right me-1"></i> Stock Transactions
        </a>
    </div>

    <div class="table-responsive">
        @if($products->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Purchase Price</th>
                    <th>Selling Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                @php $isLowStock = $product->stock_quantity <= $product->min_stock_level; @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:0.875rem;">{{ $product->name }}</div>
                        @if($isLowStock)
                        <span style="font-size:0.72rem;color:#ef4444;">
                            <i class="bi bi-exclamation-triangle me-1"></i>Low Stock
                        </span>
                        @endif
                    </td>
                    <td>
                        <code style="font-size:0.8rem;background:#f0f2f5;padding:2px 8px;border-radius:4px;">
                            {{ $product->sku }}
                        </code>
                    </td>
                    <td style="font-size:0.85rem;">
                        {{ $product->category->name ?? '—' }}
                    </td>
                    <td style="font-size:0.85rem;">₹{{ number_format($product->purchase_price, 0) }}</td>
                    <td style="font-size:0.85rem;font-weight:600;">
                        ₹{{ number_format($product->selling_price, 0) }}
                    </td>
                    <td>
                        <span class="badge badge-status {{ $isLowStock ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                            {{ $product->stock_quantity }} units
                        </span>
                        <div style="font-size:0.72rem;color:#94a3b8;">Min: {{ $product->min_stock_level }}</div>
                    </td>
                    <td>
                        <span class="badge badge-status {{ $product->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('products.show', $product) }}"
                               class="btn btn-sm btn-light" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product) }}"
                               class="btn btn-sm btn-light" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
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
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm mt-2">
                <i class="bi bi-plus-lg me-1"></i> Add Product
            </a>
        </div>
        @endif
    </div>
</div>

@endsection