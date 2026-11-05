<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $fillable = [
        'title','description','customer','estimated_value','stage','owner_id','source','probability','expected_close_date','next_action_date','last_contacted_at',
        'kdv_percent','kdv_amount','estimated_value_kdv'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function feasibilityStudies()
    {
        return $this->hasMany(\App\Models\FeasibilityStudy::class);
    }

    public function addActivity($text)
    {
        $stamp = now()->format('Y-m-d H:i');
        $entry = "[{$stamp}] " . $text . "\n";
        $this->activity_log = trim(($this->activity_log ?? '') . "\n" . $entry);
        $this->save();

        // also insert normalized activity
        $userId = auth()->check() ? auth()->id() : null;
        \App\Models\OpportunityActivity::create([
            'opportunity_id' => $this->id,
            'user_id' => $userId,
            'type' => 'note',
            'note' => $text,
        ]);
    }
}
