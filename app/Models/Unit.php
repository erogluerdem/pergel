<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'unit_no',
        'block',
        'floor',
        'unit_type',
        'area',
        'room_count',
        'bathroom_count',
        'list_price',
        'selling_price',
        'max_discount_rate',
        'currency',
        'status',
        'description',
    ];

    protected $casts = [
        'area' => 'decimal:2',
        'list_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'max_discount_rate' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function reservations()
    {
        return $this->hasMany(SalesReservation::class);
    }



    public function getAvailableStatusAttribute()
    {
        return $this->status === 'available';
    }

    public function getMaxDiscountAmountAttribute()
    {
        return ($this->list_price * $this->max_discount_rate) / 100;
    }
}

