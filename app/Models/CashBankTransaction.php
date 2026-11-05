<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'amount', 'description', 'transaction_date', 'direction', 'capital_transaction_id', 'account_id', 'currency', 'exchange_rate', 'amount_tl', 'project_id'
    ];

    public function capitalTransaction()
    {
        return $this->belongsTo(CapitalTransaction::class);
    }

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'account_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
