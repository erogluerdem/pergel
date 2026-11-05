<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesReservation extends Model
{
    use SoftDeletes;

    protected $table = 'sales_reservations';

    protected $fillable = [
        'customer_id',
        'unit_id',
        'created_by',
        'option_no',
        'option_date',
        'expiry_date',
        'option_price',
        'discount_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'option_date' => 'date',
        'expiry_date' => 'date',
        'option_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired()
    {
        return $this->expiry_date->isPast() && $this->status === 'active';
    }
}
