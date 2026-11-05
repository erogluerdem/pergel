<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QhseInspection extends Model
{
    use HasFactory;

    protected $table = 'qhse_inspections';

    protected $fillable = ['title','discipline','status','items','due_date'];

    protected $casts = [
        'items' => 'array',
        'due_date' => 'datetime',
    ];

    public function punches()
    {
        return $this->hasMany(QhsePunch::class, 'inspection_id');
    }
}
