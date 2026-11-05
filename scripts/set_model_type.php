<?php
// Usage: php set_model_type.php <id> <type>
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\BimModel;

$id = $argv[1] ?? null;
$type = $argv[2] ?? null;
if (! $id || ! $type) {
    fwrite(STDERR, "Usage: php set_model_type.php <id> <type>\n"); exit(2);
}
$m = BimModel::find($id);
if (! $m) { fwrite(STDERR, "Model not found: $id\n"); exit(3); }
$m->file_type = strtolower($type);
$m->save();
echo "Updated model {$m->id} file_type={$m->file_type}\n";
echo "file_path={$m->file_path}\nstatus={$m->status}\n";
