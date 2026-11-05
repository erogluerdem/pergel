<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'invoice_id',
        'plan_no',
        'total_amount',
        'installment_count',
        'interval_days',
        'down_payment_percentage',
        'is_template',
        'start_date',
        'payment_interval_days',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'total_amount' => 'decimal:2',
        'down_payment_percentage' => 'decimal:2',
        'is_template' => 'boolean',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function getNextPaymentDateAttribute()
    {
        return $this->start_date ? $this->start_date->addDays($this->interval_days ?? $this->payment_interval_days ?? 30) : null;
    }

    public function getInstallmentAmountAttribute()
    {
        if (!$this->total_amount || !$this->installment_count) {
            return 0;
        }
        return $this->total_amount / $this->installment_count;
    }
}


