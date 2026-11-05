<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractClause extends Model
{
    use HasFactory;

    protected $fillable = ['title','content','category','created_by','status'];
}
