<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_code',
        'project_id',
        'name',
        'description',
        'city',
        'district',
        'neighborhood',
        'address',
        'latitude',
        'longitude',
        'area',
        'building_area',
        'zoning_status',
        'floor_ratio',
        'coverage_ratio',
        'land_value',
        'development_potential_value',
        'potential_notes',
        'investment_risk_level',
        'deed_number',
        'block_number',
        'parcel_number',
        'deed_date',
        'owner_name',
        'deed_description',
        'legal_status',
        'legal_notes',
        'legal_status_check_date',
        'document_count',
        'last_document_upload',
        'status',
        'workflow_status',
        'user_id',
        'assigned_to_id',
    ];

    protected $casts = [
        'area' => 'decimal:2',
        'building_area' => 'decimal:2',
        'floor_ratio' => 'decimal:2',
        'coverage_ratio' => 'decimal:2',
        'land_value' => 'decimal:2',
        'development_potential_value' => 'decimal:2',
        'deed_date' => 'date',
        'legal_status_check_date' => 'date',
        'last_document_upload' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function documents()
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function legalProcesses()
    {
        return $this->hasMany(LegalProcess::class);
    }

    public function evaluations()
    {
        return $this->hasMany(PropertyEvaluation::class);
    }

    // ==================== SCOPES ====================

    public function scopeVacant($query)
    {
        return $query->where('status', 'vacant');
    }

    public function scopeAcquired($query)
    {
        return $query->where('status', 'acquired');
    }

    public function scopeInDevelopment($query)
    {
        return $query->where('status', 'in_development');
    }

    public function scopeDeveloped($query)
    {
        return $query->where('status', 'developed');
    }

    public function scopeClear($query)
    {
        return $query->where('legal_status', 'clear');
    }

    public function scopeDisputed($query)
    {
        return $query->where('legal_status', 'disputed');
    }

    public function scopeHighRisk($query)
    {
        return $query->where('investment_risk_level', 'high');
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('property_code', 'like', "%{$search}%")
            ->orWhere('name', 'like', "%{$search}%")
            ->orWhere('deed_number', 'like', "%{$search}%")
            ->orWhere('address', 'like', "%{$search}%");
    }

    // ==================== ACCESSORS & MUTATORS ====================

    public function getTotalValueAttribute()
    {
        return $this->land_value + ($this->development_potential_value ?? 0);
    }

    public function getIsHighRiskAttribute()
    {
        return $this->investment_risk_level === 'high';
    }

    public function getIsDisputedAttribute()
    {
        return $this->legal_status === 'disputed';
    }

    public function getHasCriticalDocumentsAttribute()
    {
        return $this->documents()->where('is_critical', true)->exists();
    }

    // ==================== CUSTOM METHODS ====================

    public function getStatusBadge()
    {
        $badges = [
            'vacant' => '<span class="badge bg-secondary">Boş</span>',
            'acquired' => '<span class="badge bg-info">Satın Alındı</span>',
            'in_development' => '<span class="badge bg-warning">Gelişim Aşamasında</span>',
            'developed' => '<span class="badge bg-success">Geliştirildi</span>',
            'inactive' => '<span class="badge bg-danger">İnaktif</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-light">Bilinmiyor</span>';
    }

    public function getLegalStatusBadge()
    {
        $badges = [
            'clear' => '<span class="badge bg-success">Temiz</span>',
            'disputed' => '<span class="badge bg-danger">Anlaşmazlıklı</span>',
            'under_process' => '<span class="badge bg-info">İşlemde</span>',
            'restricted' => '<span class="badge bg-warning">Kısıtlı</span>',
        ];

        return $badges[$this->legal_status] ?? '<span class="badge bg-light">Bilinmiyor</span>';
    }

    public function addDocument($file, $category, $metadata = [])
    {
        return $this->documents()->create([
            'document_name' => $file->getClientOriginalName(),
            'file_name' => $file->hashName(),
            'file_path' => $file->store('properties/' . $this->id),
            'file_type' => $file->extension(),
            'file_size' => $file->getSize(),
            'document_category' => $category,
            'uploaded_by_id' => auth()->id(),
            'description' => $metadata['description'] ?? null,
            'document_date' => $metadata['document_date'] ?? null,
            'validity_date' => $metadata['validity_date'] ?? null,
            'is_critical' => $metadata['is_critical'] ?? false,
        ]);
    }

    public function addLegalProcess($processData)
    {
        return $this->legalProcesses()->create($processData);
    }

    public function addEvaluation($evaluationData)
    {
        return $this->evaluations()->create($evaluationData);
    }

    public function getPendingLegalProcesses()
    {
        return $this->legalProcesses()
            ->whereIn('status', ['not_started', 'in_progress', 'pending_approval'])
            ->get();
    }

    public function getDelayedLegalProcesses()
    {
        return $this->legalProcesses()
            ->where('is_delayed', true)
            ->get();
    }

    public function calculateDevelopmentPotential()
    {
        if (!$this->area) return null;

        $baseValue = $this->land_value ?? 0;
        $infrastructureBonus = $this->floor_ratio * 100000; // Örnek hesaplama
        $locationBonus = $this->accessibility_score * 50000 ?? 0; // Erişilebilirlik bonusu

        return $baseValue + $infrastructureBonus + $locationBonus;
    }

    public function getDeedsRequiredAttribute()
    {
        return !$this->deed_number;
    }

    public function getZoningApprovalRequiredAttribute()
    {
        return empty($this->zoning_status);
    }

    public function markAsAcquired()
    {
        $this->update(['status' => 'acquired']);
    }

    public function markAsDeveloped()
    {
        $this->update(['status' => 'developed']);
    }

    public function markAsInactive()
    {
        $this->update(['status' => 'inactive']);
    }

    public function updateLegalStatus($status, $notes = null)
    {
        $this->update([
            'legal_status' => $status,
            'legal_notes' => $notes,
            'legal_status_check_date' => now()->toDateString(),
        ]);
    }
}
