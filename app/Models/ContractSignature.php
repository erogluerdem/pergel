<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'user_id',
        'signer_name',
        'signer_email',
        'signer_role',
        'token',
        'status',
        'signed_at',
        'signature_data',
        'ip_address',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'signature_data' => 'array',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
