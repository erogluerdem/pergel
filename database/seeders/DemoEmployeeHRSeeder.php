<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Employee;
use App\Models\EmployeeAssignment;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoEmployeeHRSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        if ($projects->isEmpty()) {
            return;
        }

        $employeesData = [
            [
                'first_name' => 'Ahmet',
                'last_name' => 'Yılmaz',
                'position' => 'Proje Müdürü (İnşaat Y. Müh.)',
                'department' => 'Proje Yönetimi',
                'phone' => '0532 450 12 34',
                'email' => 'ahmet.yilmaz@pergel.com',
                'hire_date' => '2020-03-15',
                'status' => 'active',
                'certificates' => [
                    ['certificate_type' => 'A Sınıfı İSG Uzmanlık Sertifikası', 'certificate_number' => 'ISG-A-2022-849', 'issue_date' => '2022-05-10', 'expiry_date' => '2027-05-10', 'issuing_authority' => 'ÇSGB'],
                    ['certificate_type' => 'PMP Proje Yönetimi Sertifikası', 'certificate_number' => 'PMP-391028', 'issue_date' => '2021-08-20', 'expiry_date' => '2026-08-20', 'issuing_authority' => 'PMI'],
                ]
            ],
            [
                'first_name' => 'Mehmet',
                'last_name' => 'Kaya',
                'position' => 'Şantiye Şefi (İnşaat Müh.)',
                'department' => 'Saha Uygulama',
                'phone' => '0533 555 98 76',
                'email' => 'mehmet.kaya@pergel.com',
                'hire_date' => '2021-06-01',
                'status' => 'active',
                'certificates' => [
                    ['certificate_type' => 'B Sınıfı İSG Sertifikası', 'certificate_number' => 'ISG-B-2021-112', 'issue_date' => '2021-02-15', 'expiry_date' => '2026-09-01', 'issuing_authority' => 'ÇSGB'],
                    ['certificate_type' => 'Yüksekte Çalışma Eğitici Sertifikası', 'certificate_number' => 'YCE-4412', 'issue_date' => '2023-01-10', 'expiry_date' => '2026-08-28', 'issuing_authority' => 'İSGÜM'],
                ]
            ],
            [
                'first_name' => 'Elif',
                'last_name' => 'Demir',
                'position' => 'Mekanik Tesisat Mühendisi',
                'department' => 'MEP Tesisat',
                'phone' => '0535 321 65 43',
                'email' => 'elif.demir@pergel.com',
                'hire_date' => '2022-01-10',
                'status' => 'active',
                'certificates' => [
                    ['certificate_type' => 'Yangın Tesisatı Uzmanlık Belgesi', 'certificate_number' => 'YNG-2022-99', 'issue_date' => '2022-11-05', 'expiry_date' => '2027-11-05', 'issuing_authority' => 'MMO'],
                ]
            ],
            [
                'first_name' => 'Caner',
                'last_name' => 'Öztürk',
                'position' => 'Elektrik Başmühendisi',
                'department' => 'Elektrik & Otomasyon',
                'phone' => '0536 789 01 23',
                'email' => 'caner.ozturk@pergel.com',
                'hire_date' => '2019-11-01',
                'status' => 'active',
                'certificates' => [
                    ['certificate_type' => 'Yüksek Gerilim İşletme Sorumluluğu', 'certificate_number' => 'YG-EMO-8841', 'issue_date' => '2020-04-12', 'expiry_date' => '2025-04-12', 'issuing_authority' => 'EMO'],
                ]
            ],
            [
                'first_name' => 'Hakan',
                'last_name' => 'Şahin',
                'position' => 'Kule Vinç Operatörü',
                'department' => 'Makine Parkı',
                'phone' => '0542 999 11 22',
                'email' => 'hakan.sahin@pergel.com',
                'hire_date' => '2023-04-01',
                'status' => 'active',
                'certificates' => [
                    ['certificate_type' => 'G Sınıfı Kule Vinç Ehliyeti & MYK', 'certificate_number' => 'MYK-VNC-902', 'issue_date' => '2021-09-01', 'expiry_date' => '2026-09-01', 'issuing_authority' => 'MEB & MYK'],
                ]
            ],
            [
                'first_name' => 'Zeynep',
                'last_name' => 'Arslan',
                'position' => 'Kalite Kontrol (QA/QC) Uzmanı',
                'department' => 'Kalite Yönetimi',
                'phone' => '0538 444 33 22',
                'email' => 'zeynep.arslan@pergel.com',
                'hire_date' => '2022-09-15',
                'status' => 'active',
                'certificates' => [
                    ['certificate_type' => 'ISO 9001 Başdenetçi Sertifikası', 'certificate_number' => 'ISO-9001-LA-771', 'issue_date' => '2022-03-20', 'expiry_date' => '2027-03-20', 'issuing_authority' => 'TÜRKAK'],
                ]
            ],
        ];

        foreach ($employeesData as $idx => $empData) {
            $certs = $empData['certificates'];
            unset($empData['certificates']);

            $project = $projects[$idx % $projects->count()];
            $empData['current_project_id'] = $project->id;

            $employee = Employee::create($empData);

            // Assignment
            EmployeeAssignment::create([
                'employee_id' => $employee->id,
                'project_id' => $project->id,
                'start_date' => $employee->hire_date,
                'role' => $employee->position,
                'status' => 'active',
            ]);

            // Certificates
            foreach ($certs as $cert) {
                Certificate::create([
                    'employee_id' => $employee->id,
                    'certificate_type' => $cert['certificate_type'],
                    'certificate_number' => $cert['certificate_number'],
                    'issue_date' => $cert['issue_date'],
                    'expiry_date' => $cert['expiry_date'],
                    'issuing_authority' => $cert['issuing_authority'],
                    'status' => 'valid',
                ]);
            }
        }
    }
}
