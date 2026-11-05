<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcontractorWorker extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcontractor_id',
        'project_id',
        'first_name',
        'last_name',
        'tc_no',
        'job_title',
        'sgk_status',
        'entry_permit_status',
        'blocked_reason',
        'notes',
    ];

    public function subcontractor()
    {
        return $this->belongsTo(Subcontractor::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function documents()
    {
        return $this->hasMany(SubcontractorWorkerDocument::class, 'worker_id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
