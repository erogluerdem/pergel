<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderPackage extends Model
{
    protected $table = 'tender_packages';
    protected $fillable = [
        'project_id','name','scope','start_date','end_date','status','responsible_id','documents','boq',
        'procurement_type','estimated_cost','currency','contract_duration_days','guarantee_percent','tender_reference','announcement_date'
    ];

    protected $casts = [
        'documents' => 'array',
        'boq' => 'array',
        'bidders' => 'array',
        'published_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function responsible()
    {
        return $this->belongsTo(\App\Models\User::class, 'responsible_id');
    }

    // All submitted bids for this package
    public function bids()
    {
        return $this->hasMany(TenderBid::class, 'tender_package_id');
    }

    // Alias for counting distinct bidders (fallback to bids count)
    public function bidders()
    {
        return $this->hasMany(TenderBid::class, 'tender_package_id');
    }

}
