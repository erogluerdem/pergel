<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $guarded = [];
    protected $casts = [
        'meta' => 'array',
        'due_date' => 'datetime'
    ];

    public function items()
    {
        return $this->hasMany(InspectionItem::class);
    }
}
