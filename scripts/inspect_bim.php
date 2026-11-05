<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BimModel;
use Illuminate\Support\Facades\Storage;

$models = BimModel::orderBy('id','desc')->take(10)->get();
if ($models->isEmpty()) {
    echo "No bim models found\n";
    exit;
}

foreach ($models as $m) {
    $exists = ($m->file_path && Storage::exists($m->file_path)) ? 'yes' : 'no';
    echo "ID: {$m->id}\n";
    echo "  name: {$m->name}\n";
    echo "  file_path: {$m->file_path}\n";
    echo "  status: {$m->status}\n";
    echo "  processed_at: {$m->processed_at}\n";
    echo "  storage_exists: {$exists}\n";
    echo "  metadata keys: ";
    $meta = $m->metadata ?: [];
    if (is_array($meta)) echo implode(',', array_keys($meta)); else echo gettype($meta);
    echo "\n---\n";
}
