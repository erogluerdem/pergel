<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\UsesSubcontractorConnection;

class SubcontractorServiceEntry extends Model
{
    use HasFactory;
    use UsesSubcontractorConnection;

    protected $fillable = [
        'subcontractor_contract_id',
        'project_id',
        'subcontractor_user_id',
        'entry_date',
        'service_type',
        'work_description',
        'quantity',
        'unit',
        'worked_hours',
        'crew_size',
        'status',
        'attachments',
        'notes',
        'submitted_at',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'quantity' => 'decimal:2',
        'worked_hours' => 'decimal:2',
        'attachments' => 'array',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function contract()
    {
        return $this->belongsTo(SubcontractorContract::class, 'subcontractor_contract_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(SubcontractorUser::class, 'subcontractor_user_id');
    }
}
