<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    protected $fillable = [
        'asset_id',
        'template_id',
        'type',
        'title',
        'description',
        'priority',
        'status',
        'assigned_to',
        'scheduled_date',
        'completed_date',
        'cost',
        'parts_used',
        'technician_notes',
        'photo_path',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTemplate::class, 'template_id');
    }
}
