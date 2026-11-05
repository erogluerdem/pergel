<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'share', 'subcontractor_id'];

    public function subcontractor()
    {
        return $this->belongsTo(\App\Models\Subcontractor::class, 'subcontractor_id');
    }

    public function capitalTransactions()
    {
        return $this->hasMany(CapitalTransaction::class);
    }
}
