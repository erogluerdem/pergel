<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderInvitation extends Model
{
    protected $table = 'tender_invitations';
    protected $fillable = ['tender_package_id','bidder_email','token','expires_at','used_at'];
    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function package()
    {
        return $this->belongsTo(TenderPackage::class, 'tender_package_id');
    }
}
