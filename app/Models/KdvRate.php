<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KdvRate extends Model
{
    use HasFactory;

    protected $table = 'kdv_rates';
    protected $fillable = ['rate','label','sort','active'];
}
