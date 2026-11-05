<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentReminder extends Model
{
    protected $fillable = ['installment_id', 'user_id', 'type', 'days_before', 'sent', 'sent_at', 'response'];

    protected $casts = [
        'sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsSent($response = null)
    {
        $this->update([
            'sent' => true,
            'sent_at' => now(),
            'response' => $response,
        ]);
    }
}
