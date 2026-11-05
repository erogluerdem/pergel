<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SgkLaborCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'building_area_sqm',
        'structure_class',
        'unit_cost_per_sqm',
        'mandatory_labor_rate',
        'required_labor_amount',
        'reported_labor_amount',
        'remaining_labor_amount',
        'risk_status',
        'notes',
    ];

    protected $casts = [
        'building_area_sqm' => 'decimal:2',
        'unit_cost_per_sqm' => 'decimal:2',
        'mandatory_labor_rate' => 'decimal:2',
        'required_labor_amount' => 'decimal:2',
        'reported_labor_amount' => 'decimal:2',
        'remaining_labor_amount' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
