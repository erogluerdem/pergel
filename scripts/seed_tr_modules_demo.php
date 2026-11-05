<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use App\Models\SgkLaborCalculation;
use App\Models\LandOwnerContract;
use App\Models\UnitShareMatrix;
use App\Models\ConcreteTestRecord;
use App\Models\PromissoryNote;
use App\Models\TradeAsset;
use App\Models\SubcontractorWorker;
use App\Models\SubcontractorWorkerDocument;
use Illuminate\Support\Facades\DB;

$project = Project::first();
if (!$project) {
    echo "No project found.\n";
    exit(0);
}

// 7. Taşeron İSG Demo
if (SubcontractorWorker::count() === 0) {
    $sub = DB::table('subcontractors')->first();
    if (!$sub) {
        $subId = DB::table('subcontractors')->insertGetId([
            'company_name' => 'Karakaya Demir & Kalıp İnşaat Ltd. Şti.',
            'tax_number' => '9871234567',
            'phone' => '0212 555 1234',
            'email' => 'info@karakayainsaat.com',
            'contact_person' => 'Ahmet Karakaya',
            'expertise_area' => 'Kaba İnşaat & Demir',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } else {
        $subId = $sub->id;
    }

    $worker = SubcontractorWorker::create([
        'subcontractor_id' => $subId,
        'project_id' => $project->id,
        'first_name' => 'Mustafa',
        'last_name' => 'Usta',
        'tc_no' => '45678912301',
        'job_title' => 'Kalıpçı / İskele Kurulum Ustası',
        'sgk_status' => 'active',
        'entry_permit_status' => 'approved',
        'notes' => 'İSG evrakları tam.',
    ]);

    SubcontractorWorkerDocument::create([
        'worker_id' => $worker->id,
        'document_type' => 'isg_certificate',
        'issue_date' => now()->subMonths(2)->toDateString(),
        'expiry_date' => now()->addMonths(10)->toDateString(),
        'is_valid' => true,
        'notes' => '16 Saatlik Temel İSG Eğitimi Tamamlandı.',
    ]);
    echo "Taşeron İSG demo seeded.\n";
}

echo "All demo data seeded successfully!\n";
