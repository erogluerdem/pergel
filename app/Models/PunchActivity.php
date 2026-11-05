<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PunchActivity extends Model
{
    protected $guarded = [];
    protected $table = 'punchlist_activities';

    public function punch()
    {
        return $this->belongsTo(Punch::class, 'punch_id');
    }
}
