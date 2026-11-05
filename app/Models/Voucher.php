<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;
    protected $table = 'vouchers';
    protected $guarded = [];
    protected $fillable = ['type', 'date', 'account', 'counterparty', 'amount', 'payment_method', 'notes', 'created_by', 'project_id'];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function lines()
    {
        return $this->hasMany(VoucherLine::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
