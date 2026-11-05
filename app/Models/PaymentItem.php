<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    use HasFactory;
    protected $fillable = ['payment_id','pos','description','unit','contract_qty','prev_qty','this_qty','total_qty','unit_price','this_amount'];

    public function evidences()
    {
        return $this->hasMany(PaymentEvidence::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
