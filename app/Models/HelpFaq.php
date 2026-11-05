<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpFaq extends Model
{
    protected $fillable = [
        'category_name',
        'question',
        'answer',
        'sort_order',
        'remote_id',
    ];
}
