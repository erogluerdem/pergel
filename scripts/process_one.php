<?php
// usage: php scripts/process_one.php <modelId>
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if ($argc < 2) { echo "Usage: php scripts/process_one.php <modelId>\n"; exit(1); }
$modelId = intval($argv[1]);

use App\Jobs\ProcessBimModel;
use App\Models\BimModel;

$m = BimModel::find($modelId);
if (!$m) { echo "Model not found: $modelId\n"; exit(1); }

echo "Before: status={$m->status}, processed_at={$m->processed_at}\n";
ProcessBimModel::dispatchSync($modelId);
$m->refresh();
echo "After: status={$m->status}, processed_at={$m->processed_at}\n";
if ($m->metadata) echo "metadata keys: ".(is_array($m->metadata)?implode(',',array_keys($m->metadata)):gettype($m->metadata))."\n";
