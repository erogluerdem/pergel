<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'location', 'status', 'budget', 'start_date', 'end_date', 'program_id', 'progress_percent', 'spent', 'type', 'project_manager', 'region', 'health_status', 'project_code', 'contract_number', 'client_name', 'contractor_name', 'tax_number', 'tax_office', 'kdv_rate', 'kdv_amount', 'invoice_uuid', 'other_taxes', 'contact_phone', 'attachments', 'cover_image', 'location_province', 'location_district', 'latitude', 'longitude',
        // Turkey standard fields
        'building_permit_number','permit_issue_date','tapu_pafta','tapu_ada','tapu_parsel','tapu_volume','land_registry_reference','cadde_sokak','province','district','gross_area','net_area','floor_count','building_usage','structure_system','contractor_vkn','contractor_license_number','responsible_engineer_name','responsible_engineer_registration','responsible_architect_name','responsible_architect_registration','employer_name','insurance_policy_number','insurance_policy_provider','performance_bond_number','financing_source','contract_value','municipality','zoning_plan_status','environmental_permit_number','risk_classification','municipality_project_id','construction_license_expiry_date','safety_plan_reference','bim_level','project_contact_person','project_contact_email','project_contact_phone'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'attachments' => 'array',
        'budget' => 'decimal:2',
        'spent' => 'decimal:2',
    'location' => 'json',
    'latitude' => 'decimal:7',
    'longitude' => 'decimal:7',
        'other_taxes' => 'array',
        'kdv_rate' => 'decimal:2',
        'kdv_amount' => 'decimal:2',
        'permit_issue_date' => 'date',
        'gross_area' => 'decimal:2',
        'net_area' => 'decimal:2',
        'contract_value' => 'decimal:2',
        'construction_license_expiry_date' => 'date',
    ];

    // Check if the likes method already exists
    public function likes()
    {
        return $this->hasMany(ProjectLike::class);
    }

    public function closeout(): HasOne
    {
        return $this->hasOne(Closeout::class);
    }

    public function vendors()
    {
        return $this->belongsToMany(\App\Models\Vendor::class, 'project_vendor');
    }

    public function costItems(): HasMany
    {
        return $this->hasMany(CostItem::class, 'project_id');
    }

    /**
     * Milestones are stored per Program; return milestones for this project's program.
     */
    public function milestones(): HasMany
    {
        // milestones.program_id = projects.program_id
        return $this->hasMany(Milestone::class, 'program_id', 'program_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'project_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'project_id');
    }

    public function documentFolders(): HasMany
    {
        return $this->hasMany(DocumentFolder::class, 'project_id');
    }

    public function planningTasks(): HasMany
    {
        return $this->hasMany(PlanningTask::class, 'project_id');
    }

    public function progressClaims(): HasMany
    {
        return $this->hasMany(ProgressClaim::class, 'project_id');
    }

    public function submittals(): HasMany
    {
        return $this->hasMany(Submittal::class, 'project_id');
    }

    public function tenderPackages(): HasMany
    {
        return $this->hasMany(TenderPackage::class, 'project_id');
    }

    public function actionItems(): HasMany
    {
        return $this->hasMany(ActionItem::class, 'project_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class, 'project_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'project_id');
    }

    public function employeeAssignments(): HasMany
    {
        return $this->hasMany(EmployeeAssignment::class, 'project_id');
    }

    public function equipmentAssignments(): HasMany
    {
        return $this->hasMany(EquipmentAssignment::class, 'project_id');
    }

    public function meetingMinutes(): HasMany
    {
        return $this->hasMany(MeetingMinute::class, 'project_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'project_id');
    }

    public function requisitions(): HasMany
    {
        return $this->hasMany(Requisition::class, 'project_id');
    }

    public function rfis(): HasMany
    {
        return $this->hasMany(Rfi::class, 'project_id');
    }

    public function risks(): HasMany
    {
        return $this->hasMany(Risk::class, 'project_id');
    }

    public function subcontractorContracts(): HasMany
    {
        return $this->hasMany(SubcontractorContract::class, 'project_id');
    }

    public function changeOrders(): HasMany
    {
        return $this->hasMany(ChangeOrder::class, 'project_id');
    }

    public function subcontractorServiceEntries(): HasMany
    {
        return $this->hasMany(SubcontractorServiceEntry::class);
    }

    public function subcontractorMaterialEntries(): HasMany
    {
        return $this->hasMany(SubcontractorMaterialEntry::class);
    }

    // Accessor for calculated progress percent from planning tasks
    public function getCalculatedProgressPercentAttribute()
    {
        $tasks = $this->planningTasks;
        if ($tasks->isEmpty()) {
            return 0;
        }
        return round($tasks->avg('progress'), 2);
    }

    // Accessor for calculated spent from approved payments
    public function getCalculatedSpentAttribute()
    {
        return $this->payments()->where('approved', true)->sum('amount');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function villas(): HasMany
    {
        return $this->hasMany(Villa::class);
    }

    /**
     * Get the project manager
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'project_manager');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}
