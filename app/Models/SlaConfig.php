<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlaConfig extends Model
{
    protected $fillable = [
        'project_id',
        'issue_category',
        'response_time',
        'resolution_time',
        'priority_weight',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('issue_category', $category);
    }

    public function getResponseTimeInHoursAttribute()
    {
        return round($this->response_time / 3600, 2);
    }

    public function getResolutionTimeInHoursAttribute()
    {
        return round($this->resolution_time / 3600, 2);
    }
}
