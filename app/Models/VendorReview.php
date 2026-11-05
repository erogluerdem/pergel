<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorReview extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id','project_id','quality','schedule','safety','communication','comments','created_by'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
