<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentMaintenance extends Model
{
    protected $fillable = [
        'equipment_id',
        'maintenance_date',
        'type',
        'title',
        'description',
        'cost',
        'working_hours_before',
        'working_hours_after',
        'performed_by',
        'parts_used',
        'notes'
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'cost' => 'decimal:2',
        'working_hours_before' => 'integer',
        'working_hours_after' => 'integer',
        'parts_used' => 'array' // JSON olarak sakla
    ];

    // Relationships
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
