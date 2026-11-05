<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = ['program_id','title','due_date','status','description'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
