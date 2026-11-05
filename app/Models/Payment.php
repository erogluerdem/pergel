<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contract;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['no','project_id','contractor','contract','date','requested','approved','status','notes','subcontractor_contract_id'];

    public function items()
    {
        return $this->hasMany(PaymentItem::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function subcontractorContract()
    {
        return $this->belongsTo(SubcontractorContract::class);
    }
}
