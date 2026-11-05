<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarrantyClaim extends Model
{
    protected $fillable = [
        'closeout_id',
        'claim_no',
        'subject',
        'notification_date',
        'equipment_id',
        'contractor_id',
        'status',
        'evidence_photo',
        'description',
    ];

    protected $casts = [
        'notification_date' => 'date',
    ];

    public function closeout(): BelongsTo
    {
        return $this->belongsTo(Closeout::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'contractor_id');
    }
}
