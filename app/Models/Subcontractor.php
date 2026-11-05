<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\UsesSubcontractorConnection;

class Subcontractor extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UsesSubcontractorConnection;

    protected $fillable = [
        'company_name',
        'address',
        'tax_number',
        'phone',
        'email',
        'contact_person',
        'expertise_area',
        'performance_score',
        'safety_score',
        'logo',
        'status',
        'active_projects',
        'documents',
    ];

    protected $casts = [
        'performance_score' => 'decimal:2',
        'safety_score' => 'decimal:2',
        'documents' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(SubcontractorUser::class);
    }

    public function contracts()
    {
        return $this->hasMany(SubcontractorContract::class);
    }

    public function serviceEntries()
    {
        return $this->hasManyThrough(
            SubcontractorServiceEntry::class,
            SubcontractorContract::class,
            'subcontractor_id',
            'subcontractor_contract_id'
        );
    }

    public function materialEntries()
    {
        return $this->hasManyThrough(
            SubcontractorMaterialEntry::class,
            SubcontractorContract::class,
            'subcontractor_id',
            'subcontractor_contract_id'
        );
    }
}
