<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataImport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'module',
        'status',
        'file_path',
        'file_name',
        'total_rows',
        'imported_rows',
        'skipped_rows',
        'error_rows',
        'error_messages',
        'mapping',
    ];

    protected $casts = [
        'mapping' => 'json',
        'error_messages' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressPercentage(): int
    {
        if ($this->total_rows === 0) return 0;
        return (int)(($this->imported_rows / $this->total_rows) * 100);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function getErrors(): array
    {
        return $this->error_messages ?? [];
    }
}
