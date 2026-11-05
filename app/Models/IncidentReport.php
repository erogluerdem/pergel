<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    use HasFactory;

    protected $table = 'incident_reports';

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    protected $fillable = [
        'type', 'description', 'occurred_at', 'latitude', 'longitude', 'photo_path', 'reported_by', 'status', 'root_cause', 'corrective_action', 'location', 'evidence_path', 'action_responsible', 'action_deadline'
    ];
}
