<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\Subcontractor;

$sub = Subcontractor::find(1);
if(!$sub){ echo "No subcontractor 1\n"; exit(1); }

$tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'direct_test_doc.pdf';
file_put_contents($tmp, "pdf content");
$dest = "public/subcontractors/{$sub->id}/docs/test_upload_" . time() . ".pdf";
Storage::putFileAs("public/subcontractors/{$sub->id}/docs", $tmp, basename($dest));

$path = "storage/subcontractors/{$sub->id}/docs/" . basename($dest);
$docs = is_array($sub->documents) ? $sub->documents : [];
$docs[] = ['title' => 'Direct Test PDF', 'description' => 'Uploaded via script', 'url' => $path, 'uploaded_at' => now()->toDateTimeString()];
$sub->documents = $docs;
$sub->save();

echo "Stored and updated subcontractor documents\n";
