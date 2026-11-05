<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapitalTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id', 'amount', 'source', 'description', 'transaction_date'
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function cashBankTransaction()
    {
        return $this->hasOne(CashBankTransaction::class);
    }
}
