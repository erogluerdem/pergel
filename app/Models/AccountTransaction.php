<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    protected $table = 'account_transactions';

    protected $fillable = [
        'account_id','date','description','debit','credit','balance','created_by'
    ];

    protected $casts = [
        'debit' => 'float',
        'credit' => 'float',
        'balance' => 'float',
        'date' => 'date',
    ];
}
