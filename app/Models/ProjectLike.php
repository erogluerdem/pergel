<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectLike extends Model
{
    protected $fillable = ['project_id','session_id','ip'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
