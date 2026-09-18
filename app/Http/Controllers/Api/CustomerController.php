<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::when($request->search, fn($q) =>
                            $q->where('name', 'like', "%{$request->search}%")
                              ->orWhere('email', 'like', "%{$request->search}%"))
                             ->when($request->status, fn($q) =>
                             $q->where('status', $request->status))
                             ->latest()
                             ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data'    => $customers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'status'  => 'sometimes|in:active,inactive',
        ]);

        $customer = Customer::create(array_merge($validated, [
            'created_by' => $request->user()->id,
            'status'     => $validated['status'] ?? 'active',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data'    => $customer,
        ], 201);
    }

    public function show(Customer $customer)
    {
        return response()->json([
            'success' => true,
            'data'    => $customer->load(['invoices', 'activities']),
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'    => 'sometimes|string|max:255',
            'company' => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'status'  => 'sometimes|in:active,inactive',
        ]);

        $customer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data'    => $customer,
        ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully',
        ]);
    }
}