<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderBid extends Model
{
    protected $table = 'tender_bids';
    protected $fillable = ['tender_package_id','bidder_email','price','notes','documents'];
    protected $casts = ['documents'=>'array'];

    public function package()
    {
        return $this->belongsTo(TenderPackage::class, 'tender_package_id');
    }

    public function items()
    {
        return $this->hasMany(TenderBidItem::class, 'tender_bid_id');
    }
}
