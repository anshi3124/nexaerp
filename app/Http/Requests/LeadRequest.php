<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'company'        => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'source'         => 'nullable|string|max:100',
            'status'         => 'required|in:new,contacted,qualified,proposal,won,lost',
            'expected_value' => 'nullable|numeric|min:0',
            'follow_up_date' => 'nullable|date',
            'assigned_to'    => 'nullable|exists:users,id',
            'notes'          => 'nullable|string|max:1000',
        ];
    }
}