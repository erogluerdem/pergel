<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = ['program_id','name','type','allocation_start','allocation_end','notes'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
