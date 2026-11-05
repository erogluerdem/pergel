<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'name', 'type', 'bank_name', 'branch', 'account_no', 'iban', 'currency', 'balance', 'responsible', 'opening_date', 'active', 'created_by'
    ];

    protected $casts = [
        'balance' => 'float',
        'active' => 'boolean',
        'opening_date' => 'date',
    ];
}
