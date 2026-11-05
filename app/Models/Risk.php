<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    use HasFactory;

    protected $fillable = ['program_id','project_id','title','impact','likelihood','status','mitigation'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
