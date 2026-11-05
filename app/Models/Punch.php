<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Punch extends Model
{
    protected $table = 'punchlist';
    protected $guarded = [];
    protected $casts = [
        'evidence' => 'array',
        'due_date' => 'datetime'
    ];

    public function activities()
    {
        return $this->hasMany(PunchActivity::class, 'punch_id');
    }

    public function inspectionItem()
    {
        return $this->belongsTo(InspectionItem::class, 'inspection_item_id');
    }
}
