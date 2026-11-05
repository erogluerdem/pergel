<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConcreteTestRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'pouring_date',
        'concrete_class',
        'location_element',
        'quantity_m3',
        'waybill_number',
        'building_inspection_company',
        'ebis_chip_numbers',
        'day_7_strength_mpa',
        'day_7_target_percent',
        'day_28_strength_mpa',
        'day_28_target_percent',
        'status',
        'lab_report_path',
        'notes',
    ];

    protected $casts = [
        'pouring_date' => 'date',
        'quantity_m3' => 'decimal:2',
        'day_7_strength_mpa' => 'decimal:2',
        'day_7_target_percent' => 'decimal:2',
        'day_28_strength_mpa' => 'decimal:2',
        'day_28_target_percent' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
