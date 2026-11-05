<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcontractorAdvanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcontractor_id',
        'amount',
        'currency',
        'reason',
        'requested_date',
        'status',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function subcontractor()
    {
        return $this->belongsTo(Subcontractor::class, 'subcontractor_id');
    }
}
