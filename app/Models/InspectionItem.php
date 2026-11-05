<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionItem extends Model
{
    protected $guarded = [];
    protected $casts = [
        'evidence' => 'array',
        'due_date' => 'datetime'
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}
