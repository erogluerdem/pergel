<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeasibilityStudy extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','opportunity_id','project_type','total_area','region','unit_sale_price','other_revenue','land_cost','construction_cost_per_m2','soft_costs_percent','construction_months','sales_months','equity','debt','interest_rate_percent','scenario_meta','status','created_by'
    ];

    protected $casts = [
        'scenario_meta' => 'array',
    ];

    protected $dates = ['deleted_at'];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
