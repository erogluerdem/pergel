<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vendor_id',
        'project_id',
        'consultant_id',
        'currency_id',
        'no',
        'date',
        'invoice_date',
        'due_date',
        'amount',
        'paid_amount',
        'subtotal',
        'tax_amount',
        'status',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'date' => 'datetime',
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (Invoice $invoice) {
            if (!empty($invoice->date) && empty($invoice->invoice_date)) {
                $invoice->invoice_date = $invoice->date;
            } elseif (!empty($invoice->invoice_date) && empty($invoice->date)) {
                $invoice->date = $invoice->invoice_date;
            }

            if (!empty($invoice->status) && empty($invoice->payment_status)) {
                $invoice->payment_status = $invoice->status === 'open' ? 'pending' : $invoice->status;
            } elseif (!empty($invoice->payment_status) && empty($invoice->status)) {
                $invoice->status = $invoice->payment_status === 'pending' ? 'open' : $invoice->payment_status;
            }
        });
    }

    public function getInvoiceNumberAttribute()
    {
        return $this->attributes['invoice_number'] ?? $this->no;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function consultant()
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
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
