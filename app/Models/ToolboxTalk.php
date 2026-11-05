<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolboxTalk extends Model
{
    use HasFactory;

    protected $table = 'toolbox_talks';

    protected $casts = [
        'participants' => 'array',
        'date' => 'date',
    ];

    protected $fillable = [
        'date','topic','presenter_id','participants','attendance_photo_path','notes'
    ];
}
