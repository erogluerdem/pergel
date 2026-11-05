<?php
// scripts/seed_bim_demo.php
// Creates two demo BimModel records with a dummy file, runs processing and a clash test (sync) and prints the result.

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BimModel;
use App\Models\BimClashTest;
use App\Jobs\ProcessBimModel;
use App\Jobs\RunClashTest;
use Illuminate\Support\Facades\Storage;

// ensure storage dir
$dir = storage_path('app/bim/models');
if (!is_dir($dir)) mkdir($dir, 0755, true);
$filePath = 'bim/models/dummy_model.ifc';
$full = storage_path('app/') . $filePath;
file_put_contents($full, "# dummy IFC content\n" . uniqid());

echo "Created dummy file: $full\n";

// create two models
$m1 = BimModel::create(['name'=>'Demo Model A','discipline'=>'Mimari','file_type'=>'ifc','version'=>'V1','status'=>'processing','file_path'=>$filePath,'uploaded_by'=>1]);
$m2 = BimModel::create(['name'=>'Demo Model B','discipline'=>'Mekanik','file_type'=>'ifc','version'=>'V1','status'=>'processing','file_path'=>$filePath,'uploaded_by'=>1]);

echo "Created models: {$m1->id}, {$m2->id}\n";

// process them synchronously
ProcessBimModel::dispatchSync($m1->id);
ProcessBimModel::dispatchSync($m2->id);

echo "Processed models.\n";

// create clash test
$t = BimClashTest::create(['name'=>'Demo Clash','models_a'=>[$m1->id],'models_b'=>[$m2->id],'status'=>'pending','created_by'=>1]);

RunClashTest::dispatchSync($t->id);

$t->refresh();

echo "Clash Test Result (id={$t->id}, status={$t->status}):\n";
echo json_encode($t->result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

echo "Done.\n";
