<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'amount',
        'date',
        'status',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getAdvanceAmountAttribute()
    {
        return $this->amount;
    }

    public function getPaidAmountAttribute()
    {
        return $this->status === 'paid' ? $this->amount : 0;
    }

    public function getRemainingAmountAttribute()
    {
        return $this->status === 'paid' ? 0 : $this->amount;
    }
}
