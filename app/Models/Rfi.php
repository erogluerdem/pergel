<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfi extends Model
{
    use HasFactory;

    protected $table = 'rfis';
    protected $fillable = ['project_id','rfi_no','subject','description','requested_by','assigned_to','due_date','status','response','attachments'];
    protected $casts = ['due_date' => 'date','attachments' => 'array'];

    public function project(){ return $this->belongsTo(Project::class); }
}
