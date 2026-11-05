<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    use HasFactory;

    protected $table = 'permits';

    protected $casts = [
        'checklist' => 'array',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected $fillable = [
        'type',
        'permit_type',
        'description',
        'work_description',
        'location',
        'responsible_person',
        'responsible_team_id',
        'valid_from',
        'valid_to',
        'checklist',
        'risk_assessment',
        'special_conditions',
        'status',
        'approved_by',
        'requested_by',
        'approved_at',
    ];

    protected static function booted(): void
    {
        static::saving(function (Permit $permit) {
            if (!empty($permit->permit_type) && empty($permit->type)) {
                $permit->type = $permit->permit_type;
            } elseif (!empty($permit->type) && empty($permit->permit_type)) {
                $permit->permit_type = $permit->type;
            }

            if (!empty($permit->work_description) && empty($permit->description)) {
                $permit->description = $permit->work_description;
            } elseif (!empty($permit->description) && empty($permit->work_description)) {
                $permit->work_description = $permit->description;
            }
        });
    }

    public function getPermitTypeAttribute($value)
    {
        return $value ?? ($this->attributes['type'] ?? null);
    }

    public function getTypeAttribute($value)
    {
        return $value ?? ($this->attributes['permit_type'] ?? null);
    }
}
