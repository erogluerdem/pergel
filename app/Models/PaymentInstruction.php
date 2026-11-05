<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentInstruction extends Model
{
    protected $fillable = [
        'reference_type',
        'reference_id',
        'payee',
        'amount',
        'due_date',
        'project_name',
        'priority',
        'bank_account',
        'payment_date',
        'status',
        'notes',
        'created_by',
        'processed_by',
        'receipt_path'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getReference()
    {
        if ($this->reference_type === 'progress_claim') {
            return ProgressClaim::find($this->reference_id);
        } elseif ($this->reference_type === 'invoice') {
            // Invoice model varsa
            return Invoice::find($this->reference_id);
        }
        return null;
    }
}
