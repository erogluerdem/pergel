<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','vendor_id','no','date','due_date','amount','paid_amount','subtotal','tax_amount','status','notes'];

    protected $casts = [
        'date' => 'datetime',
        'due_date' => 'datetime',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function paymentPlans()
    {
        return $this->hasMany(PaymentPlan::class);
    }
}
