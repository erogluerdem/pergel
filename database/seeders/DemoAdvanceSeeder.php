<?php

namespace Database\Seeders;

use App\Models\Advance;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DemoAdvanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        if ($employees->isEmpty()) {
            return;
        }

        $sampleData = [
            [
                'employee_id' => $employees->first()->id,
                'amount' => 15000.00,
                'date' => now()->subDays(3),
                'status' => 'pending',
                'description' => 'Şantiye intikal ve acil saha ekipman tedariki için maaş avansı talebi.',
            ],
            [
                'employee_id' => $employees->skip(1)->first()?->id ?? $employees->first()->id,
                'amount' => 25000.00,
                'date' => now()->subDays(10),
                'status' => 'approved',
                'description' => 'Yıllık izin öncesi 3 taksit mahsuplaşmalı maaş avansı.',
            ],
            [
                'employee_id' => $employees->skip(2)->first()?->id ?? $employees->first()->id,
                'amount' => 10000.00,
                'date' => now()->subDays(15),
                'status' => 'paid',
                'description' => 'Saha ulaşım ve yakıt harcamaları için iş avansı.',
            ],
            [
                'employee_id' => $employees->skip(3)->first()?->id ?? $employees->first()->id,
                'amount' => 18000.00,
                'date' => now()->subDays(20),
                'status' => 'approved',
                'description' => 'Sağlık ve acil ailevi ihtiyaçlar için onaylı avans.',
            ],
            [
                'employee_id' => $employees->first()->id,
                'amount' => 8500.00,
                'date' => now()->subDays(25),
                'status' => 'paid',
                'description' => 'Aksu Ticaret Merkezi projesi ilk geçici harcırah avansı.',
            ],
        ];

        foreach ($sampleData as $data) {
            Advance::create($data);
        }
    }
}
