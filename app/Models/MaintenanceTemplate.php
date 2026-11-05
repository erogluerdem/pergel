<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceTemplate extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'frequency_unit',
        'frequency_value',
        'priority',
        'estimated_cost',
        'checklist',
        'is_active',
    ];

    protected $casts = [
        'checklist' => 'array',
        'estimated_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'template_id');
    }
}
