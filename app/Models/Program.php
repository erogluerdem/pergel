<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'manager',
        'strategic_objective',
        'status',
        'total_budget',
        'spent_to_date',
        'start_date',
        'end_date',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
