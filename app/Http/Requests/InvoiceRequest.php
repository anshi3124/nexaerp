<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'customer_id'      => 'required|exists:customers,id',
            'invoice_date'     => 'required|date',
            'due_date'         => 'nullable|date|after_or_equal:invoice_date',
            'status'           => 'required|in:draft,sent,paid,partial,overdue,cancelled',
            'tax_percent'      => 'nullable|numeric|min:0|max:100',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'notes'            => 'nullable|string|max:1000',
            'items'            => 'required|array|min:1',
            'items.*.product_id'  => 'required|exists:products,id',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'              => 'At least one invoice item is required.',
            'items.*.product_id.required' => 'Product is required for all items.',
            'items.*.quantity.min'        => 'Quantity must be at least 1.',
        ];
    }
}