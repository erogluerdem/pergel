<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Database\Seeder;

class DemoPayrollSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        if ($employees->isEmpty()) {
            return;
        }

        $payrolls = [
            [
                'employee_id' => $employees->first()->id,
                'period' => '2026-08',
                'gross_salary' => 85000.00,
                'net_salary' => 62450.00,
                'sgk_deduction' => 12750.00,
                'tax_deduction' => 9800.00,
                'overtime_bonus' => 4500.00,
                'advance_deduction' => 4500.00,
                'status' => 'pending',
            ],
            [
                'employee_id' => $employees->skip(1)->first()?->id ?? $employees->first()->id,
                'period' => '2026-08',
                'gross_salary' => 68000.00,
                'net_salary' => 49800.00,
                'sgk_deduction' => 10200.00,
                'tax_deduction' => 8000.00,
                'overtime_bonus' => 2500.00,
                'advance_deduction' => 2500.00,
                'status' => 'approved',
                'payment_date' => now()->addDays(5),
            ],
            [
                'employee_id' => $employees->skip(2)->first()?->id ?? $employees->first()->id,
                'period' => '2026-08',
                'gross_salary' => 92000.00,
                'net_salary' => 67800.00,
                'sgk_deduction' => 13800.00,
                'tax_deduction' => 10400.00,
                'overtime_bonus' => 6000.00,
                'advance_deduction' => 6000.00,
                'status' => 'paid',
                'payment_date' => now()->subDays(2),
            ],
            [
                'employee_id' => $employees->skip(3)->first()?->id ?? $employees->first()->id,
                'period' => '2026-08',
                'gross_salary' => 74000.00,
                'net_salary' => 54200.00,
                'sgk_deduction' => 11100.00,
                'tax_deduction' => 8700.00,
                'overtime_bonus' => 3200.00,
                'advance_deduction' => 3200.00,
                'status' => 'approved',
                'payment_date' => now()->addDays(5),
            ],
            [
                'employee_id' => $employees->first()->id,
                'period' => '2026-07',
                'gross_salary' => 85000.00,
                'net_salary' => 62450.00,
                'sgk_deduction' => 12750.00,
                'tax_deduction' => 9800.00,
                'overtime_bonus' => 0.00,
                'advance_deduction' => 0.00,
                'status' => 'paid',
                'payment_date' => now()->subMonths(1),
            ],
        ];

        foreach ($payrolls as $p) {
            Payroll::create($p);
        }
    }
}
