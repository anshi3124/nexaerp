<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['invoice', 'customer'])
                           ->when($request->customer_id, fn($q) =>
                               $q->where('customer_id', $request->customer_id))
                           ->latest('payment_date')
                           ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data'    => $payments,
        ]);
    }
}