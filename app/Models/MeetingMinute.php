<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingMinute extends Model
{
    use HasFactory;

    protected $table = 'meeting_minutes';
    protected $fillable = ['project_id','title','meeting_date','participants','notes','attachments'];
    protected $casts = ['meeting_date' => 'date','participants' => 'array','attachments' => 'array'];

    public function project(){ return $this->belongsTo(Project::class); }
    public function actionItems(){ return $this->hasMany(ActionItem::class, 'meeting_id'); }
}
