@extends('layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Edit Product</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">{{ $product->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.show', $product) }}" class="btn btn-light">
            <i class="bi bi-eye me-1"></i> View
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<form action="{{ route('products.update', $product) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-4">

        <div class="col-md-8">
            <div class="content-card">
                <div class="card-header">
                    <h6><i class="bi bi-box me-2"></i>Product Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-8">
                            <label class="form-label fw-500">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $product->name) }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-500">SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku"
                                   class="form-control @error('sku') is-invalid @enderror"
                                   value="{{ old('sku', $product->sku) }}">
                            @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active"   {{ old('status', $product->status) == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-500">Purchase Price (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="purchase_price" step="0.01"
                                       class="form-control"
                                       value="{{ old('purchase_price', $product->purchase_price) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-500">Selling Price (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="selling_price" step="0.01"
                                       class="form-control"
                                       value="{{ old('selling_price', $product->selling_price) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-500">Stock Quantity</label>
                            <input type="number" name="stock_quantity"
                                   class="form-control"
                                   value="{{ old('stock_quantity', $product->stock_quantity) }}">
                            <div class="form-text text-warning">
                                <i class="bi bi-info-circle me-1"></i>
                                Use Stock Transactions to update stock properly.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-500">Minimum Stock Level</label>
                            <input type="number" name="min_stock_level"
                                   class="form-control"
                                   value="{{ old('min_stock_level', $product->min_stock_level) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3"
                                      class="form-control">{{ old('description', $product->description) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="content-card mb-3">
                <div class="card-header">
                    <h6>Current Stats</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div style="font-size:0.75rem;color:#94a3b8;">Current Stock</div>
                        <div style="font-size:1.3rem;font-weight:700;color:{{ $product->stock_quantity <= $product->min_stock_level ? '#ef4444' : '#22c55e' }};">
                            {{ $product->stock_quantity }} units
                        </div>
                    </div>
                    <div class="mb-3">
                        <div style="font-size:0.75rem;color:#94a3b8;">SKU</div>
                        <code>{{ $product->sku }}</code>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:#94a3b8;">Category</div>
                        <div style="font-size:0.875rem;">{{ $product->category->name ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Update Product
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </div>

    </div>
</form>

@endsection