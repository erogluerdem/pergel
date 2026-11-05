<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QhseTemplate extends Model
{
    protected $table = 'qhse_templates';
    protected $guarded = [];
    protected $casts = [
        'items' => 'array'
    ];
}
