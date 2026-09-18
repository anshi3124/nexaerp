<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockTransactionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:stock_in,stock_out,adjustment',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string|max:500',
        ];
    }
}