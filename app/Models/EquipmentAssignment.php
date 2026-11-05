<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentAssignment extends Model
{
    protected $fillable = [
        'equipment_id',
        'project_id',
        'start_date',
        'end_date',
        'operator',
        'purpose',
        'estimated_hours',
        'actual_hours',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'estimated_hours' => 'integer',
        'actual_hours' => 'integer'
    ];

    // Relationships
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
