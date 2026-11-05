<?php
// Usage: php trigger_convert.php <id>
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\BimModel;
use App\Jobs\ConvertRvtJob;

$id = $argv[1] ?? null;
if (! $id) { fwrite(STDERR, "Usage: php trigger_convert.php <id>\n"); exit(2); }
$m = BimModel::find($id);
if (! $m) { fwrite(STDERR, "Model not found: $id\n"); exit(3); }

echo "Before: id={$m->id} file_type={$m->file_type} status={$m->status} file_path={$m->file_path}\n";
try {
    ConvertRvtJob::dispatchSync($m->id);
} catch (\Exception $e) {
    fwrite(STDERR, "Convert job failed: " . $e->getMessage() . "\n"); exit(4);
}

$m->refresh();
echo "After: id={$m->id} file_type={$m->file_type} status={$m->status} file_path={$m->file_path}\n";
if ($m->file_path && file_exists(storage_path('app/'.$m->file_path))) {
    $size = filesize(storage_path('app/'.$m->file_path));
    echo "Converted file exists: storage/{$m->file_path} (size={$size})\n";
} else {
    echo "Converted file not found in storage.\n";
}
