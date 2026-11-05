<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CloseoutItem extends Model
{
    protected $fillable = [
        'closeout_id',
        'category',
        'item',
        'status',
        'documents',
        'notes',
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    public function closeout(): BelongsTo
    {
        return $this->belongsTo(Closeout::class);
    }
}
