<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanningTask extends Model
{
    use HasFactory;

    protected $table = 'planning_tasks';

    protected $fillable = [
        'project_id',
        'name',
        'start_date',
        'end_date',
        'duration',
        'progress',
        'responsible',
        'notes',
        'dependencies',
        'resources',
        'budget_wbs',
        'documents',
        'critical_path',
        'is_milestone',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'dependencies' => 'array',
        'resources' => 'array',
        'documents' => 'array',
        'critical_path' => 'boolean',
        'is_milestone' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
