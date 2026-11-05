<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitShareMatrix extends Model
{
    use HasFactory;

    protected $fillable = [
        'land_owner_contract_id',
        'unit_id',
        'owner_type',
        'stage_requirement',
        'is_deed_transferred',
        'deed_transfer_date',
        'notes',
    ];

    protected $casts = [
        'is_deed_transferred' => 'boolean',
        'deed_transfer_date' => 'date',
    ];

    public function landOwnerContract()
    {
        return $this->belongsTo(LandOwnerContract::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
