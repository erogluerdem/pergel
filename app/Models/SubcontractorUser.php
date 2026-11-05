<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Concerns\UsesSubcontractorConnection;

class SubcontractorUser extends Authenticatable
{
    use HasFactory, Notifiable, UsesSubcontractorConnection;

    protected $fillable = [
        'subcontractor_id',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function subcontractor()
    {
        return $this->belongsTo(Subcontractor::class);
    }

    public function contracts()
    {
        return $this->belongsToMany(SubcontractorContract::class, 'subcontractor_user_contract')->withTimestamps();
    }

    public function serviceEntries()
    {
        return $this->hasMany(SubcontractorServiceEntry::class);
    }

    public function materialEntries()
    {
        return $this->hasMany(SubcontractorMaterialEntry::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
