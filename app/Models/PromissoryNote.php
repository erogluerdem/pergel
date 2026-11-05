<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromissoryNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'project_id',
        'unit_id',
        'note_number',
        'amount',
        'due_date',
        'debtor_name',
        'debtor_tc_vkn',
        'guarantor_name',
        'status',
        'endorsed_to_supplier',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
