<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\UsesSubcontractorConnection;

class SubcontractorMaterialEntry extends Model
{
    use HasFactory;
    use UsesSubcontractorConnection;

    protected $fillable = [
        'subcontractor_contract_id',
        'project_id',
        'subcontractor_user_id',
        'delivery_date',
        'material_name',
        'specification',
        'quantity',
        'unit',
        'unit_price',
        'status',
        'attachments',
        'notes',
        'submitted_at',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'attachments' => 'array',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function contract()
    {
        return $this->belongsTo(SubcontractorContract::class, 'subcontractor_contract_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(SubcontractorUser::class, 'subcontractor_user_id');
    }
}
