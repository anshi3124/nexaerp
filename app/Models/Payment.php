<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['invoice_id','customer_id','created_by','amount','payment_date','payment_method','reference_number','notes'];
    protected $casts = ['payment_date' => 'date'];
}