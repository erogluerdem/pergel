<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpVideoTutorial extends Model
{
    protected $fillable = [
        'category_name',
        'title',
        'description',
        'duration',
        'video_url',
        'remote_id',
    ];
}
