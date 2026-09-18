@extends('layouts.app')

@section('title', 'Edit Invoice')
@section('page-title', 'Edit Invoice')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Edit {{ $invoice->invoice_number }}</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Update invoice details</p>
    </div>
    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
    @csrf @method('PUT')

    <div class="row g-4">
        <div class="col-md-8">
            <div class="content-card mb-4">
                <div class="card-header">
                    <h6><i class="bi bi-info-circle me-2"></i>Invoice Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Customer</label>
                            <select name="customer_id" class="form-select">
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['draft','sent','paid','partial','overdue','cancelled'] as $s)
                                <option value="{{ $s }}" {{ old('status', $invoice->status) == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Invoice Date</label>
                            <input type="date" name="invoice_date" class="form-control"
                                   value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control"
                                   value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tax (%)</label>
                            <input type="number" name="tax_percent" id="taxPercent"
                                   class="form-control"
                                   value="{{ old('tax_percent', $invoice->tax_percent) }}"
                                   step="0.01" min="0" max="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Discount (%)</label>
                            <input type="number" name="discount_percent" id="discountPercent"
                                   class="form-control"
                                   value="{{ old('discount_percent', $invoice->discount_percent) }}"
                                   step="0.01" min="0" max="100">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" rows="2" class="form-control">{{ old('notes', $invoice->notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <h6><i class="bi bi-list-ul me-2"></i>Invoice Items</h6>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                        <i class="bi bi-plus-lg me-1"></i> Add Item
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th style="width:35%;">Product</th>
                                    <th style="width:12%;">Qty</th>
                                    <th style="width:18%;">Unit Price</th>
                                    <th style="width:12%;">Disc %</th>
                                    <th style="width:18%;">Total</th>
                                    <th style="width:5%;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="content-card mb-3" style="position:sticky;top:80px;">
                <div class="card-header">
                    <h6><i class="bi bi-calculator me-2"></i>Invoice Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span style="font-size:0.875rem;color:#64748b;">Subtotal</span>
                        <span style="font-size:0.875rem;font-weight:600;" id="displaySubtotal">₹0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="font-size:0.875rem;color:#64748b;">Discount</span>
                        <span style="font-size:0.875rem;color:#ef4444;" id="displayDiscount">-₹0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="font-size:0.875rem;color:#64748b;">Tax</span>
                        <span style="font-size:0.875rem;" id="displayTax">+₹0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span style="font-size:1rem;font-weight:700;">Grand Total</span>
                        <span style="font-size:1.2rem;font-weight:700;color:#667eea;" id="displayTotal">₹0.00</span>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Update Invoice
                        </button>
                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-light">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@php
$productsData = $products->map(function($p) {
    return [
        'id'    => $p->id,
        'name'  => $p->name,
        'price' => $p->selling_price,
        'stock' => $p->stock_quantity,
        'sku'   => $p->sku,
    ];
})->values()->toArray();

$existingItemsData = $invoice->items->map(function($i) {
    return [
        'product_id'       => $i->product_id,
        'quantity'         => $i->quantity,
        'unit_price'       => $i->unit_price,
        'discount_percent' => $i->discount_percent,
    ];
})->values()->toArray();
@endphp

<script>
const products      = {!! json_encode($productsData) !!};
const existingItems = {!! json_encode($existingItemsData) !!};

let itemCount = 0;

function addItem(data = null) {
    const tbody = document.getElementById('itemsBody');
    const idx   = itemCount++;

    const productOptions = products.map(p =>
        `<option value="${p.id}" data-price="${p.price}"
            ${data && data.product_id == p.id ? 'selected' : ''}>
            ${p.name} (${p.sku})
        </option>`
    ).join('');

    const row = `
    <tr id="item-row-${idx}">
        <td>
            <select name="items[${idx}][product_id]"
                    class="form-select form-select-sm"
                    onchange="onProductChange(this, ${idx})" required>
                <option value="">Select Product</option>
                ${productOptions}
            </select>
        </td>
        <td>
            <input type="number" name="items[${idx}][quantity]"
                   id="qty-${idx}" class="form-control form-control-sm"
                   value="${data ? data.quantity : 1}" min="1"
                   onchange="calcRow(${idx})" required>
        </td>
        <td>
            <input type="number" name="items[${idx}][unit_price]"
                   id="price-${idx}" class="form-control form-control-sm"
                   value="${data ? data.unit_price : 0}" step="0.01" min="0"
                   onchange="calcRow(${idx})" required>
        </td>
        <td>
            <input type="number" name="items[${idx}][discount_percent]"
                   id="disc-${idx}" class="form-control form-control-sm"
                   value="${data ? data.discount_percent : 0}" step="0.01" min="0" max="100"
                   onchange="calcRow(${idx})">
        </td>
        <td>
            <input type="text" id="total-${idx}"
                   class="form-control form-control-sm bg-light"
                   value="₹0.00" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-light text-danger"
                    onclick="removeItem(${idx})">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>`;

    tbody.insertAdjacentHTML('beforeend', row);
    calcRow(idx);
}

function onProductChange(select, idx) {
    const option = select.options[select.selectedIndex];
    const price  = option.getAttribute('data-price') || 0;
    document.getElementById(`price-${idx}`).value = price;
    calcRow(idx);
}

function calcRow(idx) {
    const qty   = parseFloat(document.getElementById(`qty-${idx}`)?.value)   || 0;
    const price = parseFloat(document.getElementById(`price-${idx}`)?.value) || 0;
    const disc  = parseFloat(document.getElementById(`disc-${idx}`)?.value)  || 0;
    const total = (qty * price) * (1 - disc / 100);
    const el    = document.getElementById(`total-${idx}`);
    if (el) el.value = '₹' + total.toFixed(2);
    calcTotals();
}

function calcTotals() {
    let subtotal = 0;
    document.querySelectorAll('[id^="total-"]').forEach(el => {
        subtotal += parseFloat(el.value.replace('₹', '')) || 0;
    });
    const discPercent = parseFloat(document.getElementById('discountPercent')?.value) || 0;
    const taxPercent  = parseFloat(document.getElementById('taxPercent')?.value)      || 0;
    const discount = subtotal * (discPercent / 100);
    const taxable  = subtotal - discount;
    const tax      = taxable * (taxPercent / 100);
    const total    = taxable + tax;
    document.getElementById('displaySubtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('displayDiscount').textContent = '-₹' + discount.toFixed(2);
    document.getElementById('displayTax').textContent      = '+₹' + tax.toFixed(2);
    document.getElementById('displayTotal').textContent    = '₹' + total.toFixed(2);
}

function removeItem(idx) {
    const row = document.getElementById(`item-row-${idx}`);
    if (row) { row.remove(); calcTotals(); }
}

document.getElementById('taxPercent')?.addEventListener('input', calcTotals);
document.getElementById('discountPercent')?.addEventListener('input', calcTotals);

// Load existing items
existingItems.forEach(item => addItem(item));
</script>

@endsection