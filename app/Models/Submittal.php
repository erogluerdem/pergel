<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submittal extends Model
{
    use HasFactory;

    protected $table = 'submittals';
    protected $fillable = ['project_id','submittal_no','description','submitted_by','reviewers','status','due_date','attachments'];
    protected $casts = ['reviewers' => 'array','due_date' => 'date','attachments' => 'array'];

    public function project(){ return $this->belongsTo(Project::class); }

    // helper to create signed download URL for an attachment (valid for $minutes)
    public function signedAttachmentUrl($fileName, $minutes = 1440)
    {
        return \Illuminate\Support\Facades\URL::temporarySignedRoute('projects.communication.attachment.download', now()->addMinutes($minutes), ['project'=>$this->project_id, 'type'=>'submittal','id'=>$this->id,'file'=>$fileName]);
    }
}
