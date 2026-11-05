<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'project_id',
        'unit_id',
        'asset_type',
        'title',
        'valuation_amount',
        'agreed_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'valuation_amount' => 'decimal:2',
        'agreed_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
