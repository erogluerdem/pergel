<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentEvidence extends Model
{
    use HasFactory;
    // migration created table 'payment_evidences' (plural). Eloquent default would use 'payment_evidence' (evidence is uncountable),
    // so explicitly set the table name to match migration.
    protected $table = 'payment_evidences';
    protected $fillable = ['payment_item_id','filename','mime','path','uploaded_by'];

    public function item()
    {
        return $this->belongsTo(PaymentItem::class, 'payment_item_id');
    }
}
