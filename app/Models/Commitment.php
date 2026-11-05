<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commitment extends Model
{
    protected $fillable = ['project_id','cost_item_id','contract_number','amount','invoiced'];

    public function costItem()
    {
        return $this->belongsTo(CostItem::class);
    }
}
