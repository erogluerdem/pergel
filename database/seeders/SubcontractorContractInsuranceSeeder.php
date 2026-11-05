<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubcontractorContract;

class SubcontractorContractInsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contract1 = SubcontractorContract::where('contract_number', 'TSK-021-01')->first();
        $contract2 = SubcontractorContract::where('contract_number', 'TSK-034-02')->first();

        if($contract1) {
            $contract1->update([
                'insurance_expiry_dates' => [
                    'all_risk' => '2025-10-15',
                    'third_party_liability' => '2025-09-20',
                    'employer_liability' => '2025-11-01'
                ]
            ]);
        }

        if($contract2) {
            $contract2->update([
                'insurance_expiry_dates' => [
                    'all_risk' => '2025-12-01',
                    'third_party_liability' => '2025-08-15',
                    'employer_liability' => '2025-10-30'
                ]
            ]);
        }
    }
}
