<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimClashTest extends Model
{
    use HasFactory;
    protected $table = 'bim_clash_tests';
    protected $casts = ['models_a' => 'array','models_b'=>'array','result'=>'array'];
    protected $fillable = ['name','models_a','models_b','status','result','created_by'];
}
