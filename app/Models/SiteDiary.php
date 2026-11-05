<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteDiary extends Model
{
    use HasFactory;

    protected $table = 'site_diaries';

    protected $fillable = [
        'project_id',
        'entry_date',
        'weather_condition',
        'temperature',
        'worker_count',
        'work_done_notes',
        'equipment_notes',
        'safety_notes',
        'photo_urls',
        'created_by'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'photo_urls' => 'array',
        'worker_count' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
