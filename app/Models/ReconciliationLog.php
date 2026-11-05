<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconciliationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'user_id', 'action', 'email', 'success', 'filename', 'ip', 'user_agent', 'notes'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
