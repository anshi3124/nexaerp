<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Generate unique invoice number
     * Format: INV-2024-0001
     */
    public function generateInvoiceNumber(): string
    {
        $year    = now()->year;
        $prefix  = "INV-{$year}-";
        $last    = Invoice::where('invoice_number', 'like', "{$prefix}%")
                          ->orderBy('invoice_number', 'desc')
                          ->first();

        $nextNumber = $last
            ? (int) substr($last->invoice_number, -4) + 1
            : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate invoice totals from items
     */
    public function calculateTotals(array $items, float $taxPercent, float $discountPercent): array
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $itemTotal  = $item['quantity'] * $item['unit_price'];
            $itemDiscount = $itemTotal * (($item['discount_percent'] ?? 0) / 100);
            $subtotal  += $itemTotal - $itemDiscount;
        }

        $discountAmount = round($subtotal * ($discountPercent / 100), 2);
        $taxableAmount  = $subtotal - $discountAmount;
        $taxAmount      = round($taxableAmount * ($taxPercent / 100), 2);
        $grandTotal     = $taxableAmount + $taxAmount;

        return [
            'subtotal'        => round($subtotal, 2),
            'discount_amount' => $discountAmount,
            'tax_amount'      => $taxAmount,
            'grand_total'     => round($grandTotal, 2),
        ];
    }

    /**
     * Create invoice with items and update stock
     */
    public function createInvoice(array $data, array $items): Invoice
    {
        return DB::transaction(function() use ($data, $items) {

            // Calculate totals
            $totals = $this->calculateTotals(
                $items,
                $data['tax_percent'] ?? 0,
                $data['discount_percent'] ?? 0
            );

            // Create invoice
            $invoice = Invoice::create([
                'customer_id'      => $data['customer_id'],
                'created_by'       => auth()->id(),
                'invoice_number'   => $this->generateInvoiceNumber(),
                'invoice_date'     => $data['invoice_date'],
                'due_date'         => $data['due_date'] ?? null,
                'status'           => $data['status'],
                'subtotal'         => $totals['subtotal'],
                'tax_percent'      => $data['tax_percent'] ?? 0,
                'tax_amount'       => $totals['tax_amount'],
                'discount_percent' => $data['discount_percent'] ?? 0,
                'discount_amount'  => $totals['discount_amount'],
                'grand_total'      => $totals['grand_total'],
                'paid_amount'      => 0,
                'remaining_amount' => $totals['grand_total'],
                'notes'            => $data['notes'] ?? null,
            ]);

            // Create invoice items and update stock
            foreach ($items as $item) {
                $product   = Product::findOrFail($item['product_id']);
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $itemDisc  = $itemTotal * (($item['discount_percent'] ?? 0) / 100);

                InvoiceItem::create([
                    'invoice_id'       => $invoice->id,
                    'product_id'       => $product->id,
                    'quantity'         => $item['quantity'],
                    'unit_price'       => $item['unit_price'],
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'total'            => round($itemTotal - $itemDisc, 2),
                ]);

                // Deduct stock
                $before = $product->stock_quantity;
                $after  = max(0, $before - $item['quantity']);

                $product->update(['stock_quantity' => $after]);

                StockTransaction::create([
                    'product_id'      => $product->id,
                    'user_id'         => auth()->id(),
                    'invoice_id'      => $invoice->id,
                    'type'            => 'stock_out',
                    'quantity'        => $item['quantity'],
                    'quantity_before' => $before,
                    'quantity_after'  => $after,
                    'notes'           => "Invoice #{$invoice->invoice_number}",
                ]);
            }

            return $invoice;
        });
    }

    /**
     * Update invoice paid amount and status
     */
    public function updatePaymentStatus(Invoice $invoice): void
    {
        $totalPaid = $invoice->payments()->sum('amount');
        $remaining = $invoice->grand_total - $totalPaid;

        $status = match(true) {
            $totalPaid <= 0                          => $invoice->status === 'draft' ? 'draft' : 'sent',
            $totalPaid >= $invoice->grand_total      => 'paid',
            default                                  => 'partial',
        };

        $invoice->update([
            'paid_amount'      => round($totalPaid, 2),
            'remaining_amount' => round(max(0, $remaining), 2),
            'status'           => $status,
        ]);
    }
}