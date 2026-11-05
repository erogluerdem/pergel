<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Cheque;
use App\Models\Advance;
use App\Models\Invoice;
use App\Models\Project;
use Faker\Factory as Faker;

class FinanceRealDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('tr_TR');
        $departments = ['Yazılım Geliştirme', 'Muhasebe', 'Şantiye Şefi', 'İnsan Kaynakları', 'Satın Alma', 'Lojistik', 'İSG', 'Kalite Kontrol'];
        $positions = ['Müdür', 'Uzman', 'Mühendis', 'Şef', 'Asistan', 'Tekniker'];

        for ($i = 0; $i < 30; $i++) {
            $employee = Employee::firstOrCreate(
                ['email' => $faker->unique()->companyEmail],
                [
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'department' => $faker->randomElement($departments),
                    'position' => $faker->randomElement($positions),
                    'hire_date' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d')
                ]
            );
            
            $numAdvances = rand(2, 10);
            for ($j = 0; $j < $numAdvances; $j++) {
                Advance::create([
                    'employee_id' => $employee->id,
                    'date' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
                    'amount' => rand(1000, 50000),
                    'status' => ['pending', 'approved', 'paid'][$faker->numberBetween(0,2)],
                    'description' => $faker->randomElement(['Saha gideri', 'Seyahat avansı', 'Konaklama', 'Acil malzeme alımı', 'Eğitim gideri']),
                ]);
            }
        }

        for ($i = 0; $i < 50; $i++) {
            $customer = Customer::firstOrCreate(
                ['email' => $faker->unique()->companyEmail],
                [
                    'name' => $faker->company . ' ' . $faker->randomElement(['A.Ş.', 'Ltd. Şti.', 'İnşaat']),
                    'phone' => $faker->phoneNumber,
                    'tax_number' => $faker->numerify('##########'),
                ]
            );

            $numCheques = rand(3, 15);
            for ($j = 0; $j < $numCheques; $j++) {
                Cheque::create([
                    'cheque_number' => 'CK2025-' . $customer->id . '-' . $faker->unique()->randomNumber(5),
                    'bank_name' => $faker->randomElement(['Türkiye İş Bankası', 'Ziraat Bankası', 'Garanti BBVA', 'Akbank', 'Yapı Kredi', 'QNB Finansbank', 'TEB']),
                    'branch' => $faker->city,
                    'amount' => rand(20000, 2000000),
                    'issue_date' => clone_string($faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d')),
                    'due_date' => clone_string($faker->dateTimeBetween('now', '+12 months')->format('Y-m-d')),
                    'status' => $faker->randomElement(['pending', 'cashed', 'returned']),
                    'account_number' => 'TR' . rand(10, 99) . ' 0000 0000 0000 0000',
                    'customer_id' => $customer->id
                ]);
            }

            $numInvoices = rand(5, 20);
            for ($j = 0; $j < $numInvoices; $j++) {
                $subtotal = rand(10000, 5000000);
                $tax = $subtotal * 0.18;
                Invoice::create([
                    'no' => 'INV-2025-' . $customer->id . '-' . $faker->unique()->randomNumber(5),
                    'customer_id' => $customer->id,
                    'amount' => $subtotal + $tax,
                    'date' => clone_string($faker->dateTimeBetween('-12 months', 'now')->format('Y-m-d')),
                    'due_date' => clone_string($faker->dateTimeBetween('-11 months', '+2 months')->format('Y-m-d')),
                    'status' => $faker->randomElement(['open', 'partially_paid', 'paid', 'cancelled']),
                    'tax_amount' => $tax,
                    'subtotal' => $subtotal
                ]);
            }
        }
    }
}
