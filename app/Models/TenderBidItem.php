<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderBidItem extends Model
{
    protected $table = 'tender_bid_items';
    protected $fillable = ['tender_bid_id','poz_no','description','unit','quantity','unit_price','total_price'];

    public function bid()
    {
        return $this->belongsTo(TenderBid::class, 'tender_bid_id');
    }
}
