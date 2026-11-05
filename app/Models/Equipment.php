<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $fillable = [
        'name',
        'type',
        'plate_number',
        'serial_number',
        'status',
        'current_project_id',
        'operator',
        'next_maintenance_date',
        'brand',
        'model',
        'year',
        'latitude',
        'longitude',
        'working_hours',
        'fuel_level',
        'ownership',
        'purchase_date',
        'purchase_cost',
        'notes'
    ];

    protected $casts = [
        'next_maintenance_date' => 'date',
        'purchase_date' => 'date',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'purchase_cost' => 'decimal:2',
        'working_hours' => 'integer',
        'fuel_level' => 'integer',
        'year' => 'integer'
    ];

    // Relationships
    public function currentProject(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'current_project_id');
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(EquipmentMaintenance::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(EquipmentAssignment::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EquipmentDocument::class);
    }
}
