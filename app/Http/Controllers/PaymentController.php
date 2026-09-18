<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Customer;
use App\Http\Requests\PaymentRequest;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private InvoiceService $invoiceService) {}

    public function index(Request $request)
    {
        $query = Payment::with(['invoice', 'customer', 'creator'])->latest();

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments  = $query->paginate(10)->withQueryString();
        $customers = Customer::orderBy('name')->get();

        $summary = [
            'total_received' => Payment::sum('amount'),
            'this_month'     => Payment::whereMonth('payment_date', now()->month)
                                       ->whereYear('payment_date', now()->year)
                                       ->sum('amount'),
            'cash'           => Payment::where('payment_method', 'cash')->sum('amount'),
            'bank'           => Payment::where('payment_method', 'bank_transfer')->sum('amount'),
        ];

        return view('payments.index', compact('payments', 'customers', 'summary'));
    }

    public function store(PaymentRequest $request)
    {
        $invoice = Invoice::findOrFail($request->invoice_id);

        // Validate amount doesn't exceed remaining
        if ($request->amount > $invoice->remaining_amount) {
            return back()->withErrors([
                'amount' => "Amount cannot exceed remaining balance of ₹{$invoice->remaining_amount}"
            ])->withInput();
        }

        Payment::create([
            'invoice_id'       => $invoice->id,
            'customer_id'      => $invoice->customer_id,
            'created_by'       => auth()->id(),
            'amount'           => $request->amount,
            'payment_date'     => $request->payment_date,
            'payment_method'   => $request->payment_method,
            'reference_number' => $request->reference_number,
            'notes'            => $request->notes,
        ]);

        // Update invoice payment status
        $this->invoiceService->updatePaymentStatus($invoice);

        return back()->with('success', 'Payment recorded successfully!');
    }

    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;
        $payment->delete();

        // Recalculate invoice status
        $this->invoiceService->updatePaymentStatus($invoice);

        return back()->with('success', 'Payment deleted.');
    }
}