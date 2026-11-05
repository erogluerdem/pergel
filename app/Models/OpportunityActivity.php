<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpportunityActivity extends Model
{
    protected $fillable = ['opportunity_id','user_id','type','note'];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
