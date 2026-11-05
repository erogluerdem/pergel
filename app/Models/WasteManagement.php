<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WasteManagement extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'waste_managements';
    protected $guarded = [];
}
