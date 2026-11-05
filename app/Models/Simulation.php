<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Simulation extends Model
{
    use HasFactory;
    protected $fillable = ['name','type','project_id','status','config','result','created_by'];

    protected $casts = [
        'config' => 'array',
        'result' => 'array'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
