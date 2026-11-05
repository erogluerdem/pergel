<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'tax_number', 'specialty', 'status', 'performance_score',
        'contact_person', 'phone', 'email', 'address', 'city', 'country', 'iban'
    ];

    public function projects()
    {
        return $this->belongsToMany(\App\Models\Project::class, 'project_vendor');
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\VendorReview::class);
    }

}
