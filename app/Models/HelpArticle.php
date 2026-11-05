<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpArticle extends Model
{
    protected $fillable = [
        'help_category_id',
        'title',
        'slug',
        'summary',
        'content',
        'views',
        'read_time',
        'is_popular',
        'tags',
        'author',
        'remote_id',
        'last_synced_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_popular' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(HelpCategory::class, 'help_category_id');
    }
}
