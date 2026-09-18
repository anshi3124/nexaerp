@extends('layouts.app')

@section('title', $invoice->invoice_number)
@section('page-title', 'Invoice Detail')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">{{ $invoice->invoice_number }}</h4>
        <span class="badge badge-status bg-{{ $invoice->status_color }}-subtle text-{{ $invoice->status_color }}">
            {{ ucfirst($invoice->status) }}
        </span>
    </div>
    <div class="d-flex gap-2">
        @if($invoice->status !== 'paid')
        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-light">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        @endif
        <a href="{{ route('invoices.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- Invoice Details --}}
    <div class="col-md-8">

        {{-- Invoice Header --}}
        <div class="content-card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;letter-spacing:0.5px;" class="mb-1">Bill To</div>
                        <div style="font-weight:700;font-size:1rem;">{{ $invoice->customer->name }}</div>
                        @if($invoice->customer->company)
                        <div style="color:#64748b;font-size:0.875rem;">{{ $invoice->customer->company }}</div>
                        @endif
                        @if($invoice->customer->email)
                        <div style="font-size:0.82rem;color:#94a3b8;">{{ $invoice->customer->email }}</div>
                        @endif
                        @if($invoice->customer->phone)
                        <div style="font-size:0.82rem;color:#94a3b8;">{{ $invoice->customer->phone }}</div>
                        @endif
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div style="font-size:1.5rem;font-weight:800;color:#667eea;">{{ $invoice->invoice_number }}</div>
                        <div style="font-size:0.82rem;color:#94a3b8;">
                            Date: {{ $invoice->invoice_date->format('d M Y') }}
                        </div>
                        @if($invoice->due_date)
                        <div style="font-size:0.82rem;color:{{ $invoice->is_overdue ? '#ef4444' : '#94a3b8' }};">
                            Due: {{ $invoice->due_date->format('d M Y') }}
                            @if($invoice->is_overdue) <span class="badge bg-danger-subtle text-danger">Overdue</span> @endif
                        </div>
                        @endif
                        <div style="font-size:0.82rem;color:#94a3b8;">
                            Created by: {{ $invoice->creator->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Invoice Items Table --}}
        <div class="content-card mb-4">
            <div class="card-header">
                <h6><i class="bi bi-list-ul me-2"></i>Invoice Items</h6>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Discount</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $i => $item)
                        <tr>
                            <td style="color:#94a3b8;font-size:0.8rem;">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-weight:600;font-size:0.875rem;">{{ $item->product->name }}</div>
                                <code style="font-size:0.72rem;">{{ $item->product->sku }}</code>
                            </td>
                            <td style="font-size:0.875rem;">{{ $item->quantity }}</td>
                            <td style="font-size:0.875rem;">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td style="font-size:0.875rem;">{{ $item->discount_percent }}%</td>
                            <td style="font-weight:600;font-size:0.875rem;">₹{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end" style="font-size:0.85rem;color:#64748b;">Subtotal</td>
                            <td style="font-weight:600;">₹{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        @if($invoice->discount_percent > 0)
                        <tr>
                            <td colspan="5" class="text-end" style="font-size:0.85rem;color:#ef4444;">
                                Discount ({{ $invoice->discount_percent }}%)
                            </td>
                            <td style="color:#ef4444;">-₹{{ number_format($invoice->discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        @if($invoice->tax_percent > 0)
                        <tr>
                            <td colspan="5" class="text-end" style="font-size:0.85rem;color:#64748b;">
                                Tax ({{ $invoice->tax_percent }}%)
                            </td>
                            <td>+₹{{ number_format($invoice->tax_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr style="background:#f8fafc;">
                            <td colspan="5" class="text-end fw-bold">Grand Total</td>
                            <td style="font-weight:700;font-size:1.1rem;color:#667eea;">
                                ₹{{ number_format($invoice->grand_total, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end" style="color:#22c55e;">Paid Amount</td>
                            <td style="color:#22c55e;font-weight:600;">₹{{ number_format($invoice->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end fw-bold" style="color:#ef4444;">Remaining</td>
                            <td style="color:#ef4444;font-weight:700;font-size:1rem;">
                                ₹{{ number_format($invoice->remaining_amount, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @if($invoice->notes)
            <div class="px-4 py-3 border-top">
                <div style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Notes</div>
                <div style="font-size:0.875rem;">{{ $invoice->notes }}</div>
            </div>
            @endif
        </div>

        {{-- Payment History --}}
        <div class="content-card">
            <div class="card-header">
                <h6><i class="bi bi-credit-card me-2"></i>Payment History</h6>
                <span class="badge bg-light text-muted">{{ $invoice->payments->count() }}</span>
            </div>
            @if($invoice->payments->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>By</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payments as $payment)
                        <tr>
                            <td style="font-size:0.82rem;">{{ $payment->payment_date->format('d M Y') }}</td>
                            <td style="font-weight:600;color:#22c55e;">₹{{ number_format($payment->amount, 2) }}</td>
                            <td>
                                <span class="badge badge-status bg-{{ $payment->method_color }}-subtle text-{{ $payment->method_color }}">
                                    {{ $payment->method_label }}
                                </span>
                            </td>
                            <td style="font-size:0.8rem;color:#94a3b8;">
                                {{ $payment->reference_number ?? '—' }}
                            </td>
                            <td style="font-size:0.82rem;">{{ $payment->creator->name }}</td>
                            <td>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST"
                                      onsubmit="return confirm('Delete this payment?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="bi bi-credit-card" style="font-size:2rem;color:#e2e8f0;"></i>
                <p class="text-muted mt-2 mb-0" style="font-size:0.85rem;">No payments recorded yet.</p>
            </div>
            @endif
        </div>

    </div>

    {{-- Right Column --}}
    <div class="col-md-4">

        {{-- Quick Status Update --}}
        <div class="content-card mb-3">
            <div class="card-header">
                <h6><i class="bi bi-arrow-repeat me-2"></i>Update Status</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.status', $invoice) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <select name="status" class="form-select form-select-sm">
                            @foreach(['draft','sent','paid','partial','overdue','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $invoice->status == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Record Payment --}}
        @if($invoice->remaining_amount > 0)
        <div class="content-card mb-3">
            <div class="card-header">
                <h6><i class="bi bi-plus-circle me-2 text-success"></i>Record Payment</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.82rem;">Amount (₹)</label>
                        <input type="number" name="amount" step="0.01"
                               class="form-control form-control-sm @error('amount') is-invalid @enderror"
                               value="{{ $invoice->remaining_amount }}" min="0.01">
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Remaining: ₹{{ number_format($invoice->remaining_amount, 2) }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.82rem;">Payment Date</label>
                        <input type="date" name="payment_date"
                               class="form-control form-control-sm"
                               value="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.82rem;">Payment Method</label>
                        <select name="payment_method" class="form-select form-select-sm">
                            <option value="cash">💵 Cash</option>
                            <option value="bank_transfer">🏦 Bank Transfer</option>
                            <option value="upi">📱 UPI</option>
                            <option value="card">💳 Card</option>
                            <option value="other">📋 Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.82rem;">Reference Number</label>
                        <input type="text" name="reference_number"
                               class="form-control form-control-sm"
                               placeholder="UTR / Transaction ID">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-size:0.82rem;">Notes</label>
                        <textarea name="notes" rows="2"
                                  class="form-control form-control-sm"
                                  placeholder="Optional note..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-lg me-1"></i> Record Payment
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="content-card mb-3">
            <div class="card-body text-center py-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size:2rem;"></i>
                <h6 class="mt-2 text-success">Fully Paid!</h6>
                <p class="text-muted mb-0" style="font-size:0.82rem;">
                    This invoice has been paid in full.
                </p>
            </div>
        </div>
        @endif

        {{-- Invoice Summary --}}
        <div class="content-card">
            <div class="card-header"><h6>Summary</h6></div>
            <div class="card-body">
                @foreach([
                    ['Items', $invoice->items->count() . ' products'],
                    ['Subtotal', '₹' . number_format($invoice->subtotal, 2)],
                    ['Tax', $invoice->tax_percent . '% = ₹' . number_format($invoice->tax_amount, 2)],
                    ['Discount', $invoice->discount_percent . '% = ₹' . number_format($invoice->discount_amount, 2)],
                    ['Grand Total', '₹' . number_format($invoice->grand_total, 2)],
                    ['Paid', '₹' . number_format($invoice->paid_amount, 2)],
                    ['Remaining', '₹' . number_format($invoice->remaining_amount, 2)],
                ] as [$label, $value])
                <div class="d-flex justify-content-between mb-2">
                    <span style="font-size:0.82rem;color:#64748b;">{{ $label }}</span>
                    <span style="font-size:0.82rem;font-weight:600;">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection