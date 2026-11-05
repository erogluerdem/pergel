<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Villa extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'target_cost',
        'actual_cost',
        'status', // planned, in_progress, completed
    ];

    protected $casts = [
        'target_cost' => 'float',
        'actual_cost' => 'float',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function getActualCostAttribute()
    {
        return $this->expenses()->sum('amount');
    }

    public function getVarianceAttribute()
    {
        return $this->actual_cost - $this->target_cost;
    }

    public function getVariancePercentageAttribute()
    {
        return $this->target_cost > 0 ? (($this->actual_cost - $this->target_cost) / $this->target_cost) * 100 : 0;
    }
}
