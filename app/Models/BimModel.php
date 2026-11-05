<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimModel extends Model
{
    use HasFactory;

    protected $table = 'bim_models';
    protected $fillable = ['project_id','name','discipline','file_type','version','version_notes','status','file_path','file_size','uploaded_by','metadata','processed_at','thumbnail_path'];

    protected $casts = [
        'metadata' => 'array',
        'processed_at' => 'datetime'
    ];
}
