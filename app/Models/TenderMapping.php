<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderMapping extends Model
{
    protected $table = 'tender_mappings';
    protected $fillable = ['tender_package_id','tender_bid_id','tender_bid_item_id','poz_no','bidder_email','score','accepted'];

    public function package()
    {
        return $this->belongsTo(TenderPackage::class, 'tender_package_id');
    }

    public function bid()
    {
        return $this->belongsTo(TenderBid::class, 'tender_bid_id');
    }

    public function item()
    {
        return $this->belongsTo(TenderBidItem::class, 'tender_bid_item_id');
    }
}
