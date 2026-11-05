<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'type','from_account','to_account','beneficiary_name','iban','bank','amount','date','description','status','created_by','approved_by','approved_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'approved_at' => 'datetime',
    ];
}
