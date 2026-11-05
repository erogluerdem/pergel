<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandOwnerContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'land_owner_name',
        'land_owner_tc_vkn',
        'land_share_percentage',
        'contractor_share_percentage',
        'deed_transfer_terms',
        'notes',
    ];

    protected $casts = [
        'land_share_percentage' => 'decimal:2',
        'contractor_share_percentage' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function unitShareMatrices()
    {
        return $this->hasMany(UnitShareMatrix::class);
    }
}
