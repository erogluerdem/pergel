<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QhsePunch extends Model
{
    use HasFactory;

    protected $table = 'qhse_punchlist';

    protected $fillable = ['title','description','responsible','status','evidence','inspection_id','due_date'];

    protected $casts = [
        'evidence' => 'array',
        'due_date' => 'datetime',
    ];

    public function inspection()
    {
        return $this->belongsTo(QhseInspection::class, 'inspection_id');
    }
}
