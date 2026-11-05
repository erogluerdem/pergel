<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitQrCode extends Model
{
    protected $fillable = [
        'unit_id',
        'project_id',
        'qr_token',
        'qr_code_path',
        'scans_count',
        'last_scanned_at',
        'qr_label',
        'is_active',
    ];

    protected $casts = [
        'last_scanned_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Villa::class, 'unit_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function serviceRequests()
    {
        return ServiceRequest::whereJsonContains('qr_token', $this->qr_token);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function recordScan()
    {
        $this->increment('scans_count');
        $this->update(['last_scanned_at' => now()]);
    }
}
