<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    use HasFactory;

    protected $table = 'permits';

    protected $casts = [
        'checklist' => 'array',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];

    protected $fillable = [
        'permit_type','work_description','location','responsible_team_id','valid_from','valid_to','checklist','status','approved_by'
    ];
}
