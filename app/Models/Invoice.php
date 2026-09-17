<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    protected $fillable = ['customer_id','created_by','invoice_number','invoice_date','due_date','status','subtotal','tax_percent','tax_amount','discount_percent','discount_amount','grand_total','paid_amount','remaining_amount','notes'];
    protected $casts = ['invoice_date' => 'date', 'due_date' => 'date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}