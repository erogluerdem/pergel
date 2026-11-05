<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionItem extends Model
{
    use HasFactory;

    protected $table = 'action_items';
    protected $casts = ['due_date' => 'date','attachments' => 'array'];
    protected $fillable = ['meeting_id','project_id','title','assigned_to','due_date','status','notes','attachments'];

    public function meeting(){ return $this->belongsTo(MeetingMinute::class, 'meeting_id'); }
    public function project(){ return $this->belongsTo(Project::class); }
}
