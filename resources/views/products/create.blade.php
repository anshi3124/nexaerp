@extends('layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Add New Product</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Fill in the product details below</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<form action="{{ route('products.store') }}" method="POST">
    @csrf
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
                                   value="{{ old('name') }}" placeholder="e.g. Laptop Pro 15">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-500">SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku"
                                   class="form-control @error('sku') is-invalid @enderror"
                                   value="{{ old('sku') }}" placeholder="PROD-001">
                            @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-500">Status</label>
                            <select name="status" class="form-select">
                                <option value="active"   {{ old('status','active') == 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-500">Purchase Price (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="purchase_price" step="0.01"
                                       class="form-control @error('purchase_price') is-invalid @enderror"
                                       value="{{ old('purchase_price', 0) }}">
                                @error('purchase_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-500">Selling Price (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="selling_price" step="0.01"
                                       class="form-control @error('selling_price') is-invalid @enderror"
                                       value="{{ old('selling_price', 0) }}">
                                @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-500">Initial Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity"
                                   class="form-control @error('stock_quantity') is-invalid @enderror"
                                   value="{{ old('stock_quantity', 0) }}" min="0">
                            @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-500">Minimum Stock Level <span class="text-danger">*</span></label>
                            <input type="number" name="min_stock_level"
                                   class="form-control @error('min_stock_level') is-invalid @enderror"
                                   value="{{ old('min_stock_level', 5) }}" min="0">
                            <div class="form-text">Alert when stock falls below this.</div>
                            @error('min_stock_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3"
                                      class="form-control"
                                      placeholder="Product description...">{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            {{-- Profit Preview --}}
            <div class="content-card mb-3">
                <div class="card-header">
                    <h6><i class="bi bi-graph-up me-2"></i>Profit Preview</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <div style="font-size:0.75rem;color:#94a3b8;">Purchase Price</div>
                        <div id="preview-purchase" style="font-size:1.1rem;font-weight:600;">₹0</div>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <div style="font-size:0.75rem;color:#94a3b8;">Selling Price</div>
                        <div id="preview-selling" style="font-size:1.1rem;font-weight:600;">₹0</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:#94a3b8;">Profit Per Unit</div>
                        <div id="preview-profit" style="font-size:1.3rem;font-weight:700;color:#22c55e;">₹0</div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Product
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
    // Live profit calculator
    const purchaseInput = document.querySelector('[name="purchase_price"]');
    const sellingInput  = document.querySelector('[name="selling_price"]');

    function updateProfit() {
        const purchase = parseFloat(purchaseInput.value) || 0;
        const selling  = parseFloat(sellingInput.value)  || 0;
        const profit   = selling - purchase;

        document.getElementById('preview-purchase').textContent = '₹' + purchase.toLocaleString('en-IN');
        document.getElementById('preview-selling').textContent  = '₹' + selling.toLocaleString('en-IN');
        document.getElementById('preview-profit').textContent   = '₹' + profit.toLocaleString('en-IN');
        document.getElementById('preview-profit').style.color   = profit >= 0 ? '#22c55e' : '#ef4444';
    }

    purchaseInput.addEventListener('input', updateProfit);
    sellingInput.addEventListener('input', updateProfit);
    updateProfit();
</script>
@endpush