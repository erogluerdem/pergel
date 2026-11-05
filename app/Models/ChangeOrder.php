<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeOrder extends Model
{
    protected $table = 'change_orders';

    protected $fillable = [
        'project_id', 'no', 'subject', 'reason', 'status', 'cost', 'created_by', 'approved_at', 'subcontractor_contract_id'
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function subcontractorContract()
    {
        return $this->belongsTo(SubcontractorContract::class);
    }
}
