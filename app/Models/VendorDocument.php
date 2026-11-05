<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDocument extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id','type','file_path','expiry_date'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
