<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'sku'             => 'required|string|max:100|unique:products,sku,' . $this->product?->id,
            'category_id'     => 'nullable|exists:categories,id',
            'purchase_price'  => 'required|numeric|min:0',
            'selling_price'   => 'required|numeric|min:0',
            'stock_quantity'  => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'status'          => 'required|in:active,inactive',
            'description'     => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'sku.unique'            => 'This SKU already exists. Please use a different SKU.',
            'selling_price.min'     => 'Selling price cannot be negative.',
            'purchase_price.min'    => 'Purchase price cannot be negative.',
        ];
    }
}