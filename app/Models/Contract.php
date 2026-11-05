<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contracts';

    protected $fillable = [
        'name',
        'number',
        'title',
        'content',
        'project_id',
        'template_id',
        'created_by',
        'company',
        'type',
        'value',
        'paid',
        'status',
        'effective_date',
        'expiration_date',
        'start_date',
        'end_date',
        'signed_at',
        'signed_by',
        'signature_data',
        'pdf_path',
        'notes',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'paid' => 'decimal:2',
        'effective_date' => 'date',
        'expiration_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_at' => 'datetime',
        'signature_data' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function template()
    {
        return $this->belongsTo(ContractTemplate::class, 'template_id');
    }

    public function signatures()
    {
        return $this->hasMany(ContractSignature::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}

