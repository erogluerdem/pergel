<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentDocument extends Model
{
    protected $fillable = [
        'equipment_id',
        'type',
        'title',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'issue_date',
        'expiry_date',
        'issued_by',
        'notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'file_size' => 'integer'
    ];

    // Relationships
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    // Accessor for file URL
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    // Check if document is expired
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    // Check if document expires soon (within 30 days)
    public function expiresSoon()
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= 30;
    }
}
