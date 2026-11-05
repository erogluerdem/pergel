<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcontractorBid extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcontractor_id',
        'tender_id',
        'bid_amount',
        'currency',
        'notes',
        'status',
    ];

    protected $casts = [
        'bid_amount' => 'decimal:2',
    ];

    public function subcontractor()
    {
        return $this->belongsTo(Subcontractor::class, 'subcontractor_id');
    }
}
