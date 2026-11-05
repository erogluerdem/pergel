<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'tax_number', 'tax_office', 'mersis', 'contact_person', 'phone', 'email', 'address', 'city',
        'district', 'postcode', 'tc_id', 'iban', 'bank', 'country', 'balance', 'overdue_balance', 'credit_limit', 'status', 'risk_score',
        'customer_type', 'customer_segment'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'overdue_balance' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'risk_score' => 'integer',
    ];

    public function invoices()
    {
        return $this->hasMany(\App\Models\Invoice::class);
    }

    public function receipts()
    {
        return $this->hasManyThrough(\App\Models\Receipt::class, \App\Models\Invoice::class, 'customer_id', 'invoice_id');
    }

    public function reconciliationLogs()
    {
        return $this->hasMany(\App\Models\ReconciliationLog::class);
    }

    public function kvkkConsents()
    {
        return $this->hasMany(\App\Models\KvkkConsent::class);
    }

    public function options()
    {
        return $this->hasMany(\App\Models\Option::class);
    }

    public function meetings()
    {
        return $this->hasMany(\App\Models\CustomerMeeting::class);
    }

    public function salesReservations()
    {
        return $this->hasMany(\App\Models\SalesReservation::class);
    }

    public function consultantChanges()
    {
        return $this->hasMany(\App\Models\ConsultantChange::class);
    }
}
