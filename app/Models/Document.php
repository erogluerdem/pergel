<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $fillable = [
        'project_id','folder_id','contract_id','name','code','file_path','mime_type','version','status','discipline','uploaded_by','uploaded_at','replaced_document_id','metadata','subcontractor_contract_id'
    ];

    protected $casts = [
        'metadata' => 'array',
        'uploaded_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function folder()
    {
        return $this->belongsTo(DocumentFolder::class, 'folder_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function subcontractorContract()
    {
        return $this->belongsTo(SubcontractorContract::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function replacedDocument()
    {
        return $this->belongsTo(self::class, 'replaced_document_id');
    }
}
