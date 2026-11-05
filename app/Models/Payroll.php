<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period',
        'gross_salary',
        'net_salary',
        'sgk_deduction',
        'tax_deduction',
        'overtime_bonus',
        'advance_deduction',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'sgk_deduction' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'overtime_bonus' => 'decimal:2',
        'advance_deduction' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
