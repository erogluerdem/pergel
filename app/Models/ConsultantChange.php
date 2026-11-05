<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultantChange extends Model
{
    protected $fillable = [
        'customer_id',
        'old_consultant_id',
        'new_consultant_id',
        'change_date',
        'reason',
        'notes',
        'changed_by',
    ];

    protected $casts = [
        'change_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function oldConsultant()
    {
        return $this->belongsTo(User::class, 'old_consultant_id');
    }

    public function newConsultant()
    {
        return $this->belongsTo(User::class, 'new_consultant_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getDaysAgoAttribute()
    {
        return $this->change_date->diffInDays(now());
    }
}

