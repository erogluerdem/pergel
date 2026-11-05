<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PergelLicenseState extends Model
{
    protected $fillable = [
        'installation_id',
        'license_key',
        'tier',
        'modules',
        'expires_at',
        'customer',
        'validated_at',
        'renew_url',
    ];

    protected $casts = [
        'modules' => 'array',
        'expires_at' => 'datetime',
        'validated_at' => 'datetime',
    ];
}
