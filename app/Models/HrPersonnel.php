<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrPersonnel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'hr_personnels';
    protected $guarded = [];
}
