<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcontractorWorkerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'document_type',
        'file_path',
        'issue_date',
        'expiry_date',
        'is_valid',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_valid' => 'boolean',
    ];

    public function worker()
    {
        return $this->belongsTo(SubcontractorWorker::class, 'worker_id');
    }

    public function getIsExpiredAttribute()
    {
        if (!$this->expiry_date) return false;
        return $this->expiry_date < now()->toDateString();
    }
}
