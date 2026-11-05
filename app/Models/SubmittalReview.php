<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittalReview extends Model
{
    use HasFactory;

    protected $table = 'submittal_reviews';
    protected $fillable = ['submittal_id','reviewer_email','action','comments','created_by'];

    public function submittal(){ return $this->belongsTo(Submittal::class); }
    public function creator(){ return $this->belongsTo(User::class, 'created_by'); }
}
