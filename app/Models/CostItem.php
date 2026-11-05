<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostItem extends Model
{
    protected $fillable = ['project_id','parent_id','code','name','original','changes','revised','committed','actual','ttm'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function commitments()
    {
        return $this->hasMany(Commitment::class);
    }
}
