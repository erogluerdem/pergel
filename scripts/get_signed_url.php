<?php
// Prints temporary signed URL for a BimModel id
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BimModel;
use Illuminate\Support\Facades\URL;

$id = getenv('BIM_ID') ?: ($argv[1] ?? 3);
$base = $argv[2] ?? getenv('BASE_URL');
if ($base) {
    // Force the URL generator to use provided base (useful for dev server host/port)
    URL::forceRootUrl($base);
}
$m = BimModel::find($id);
if (! $m) {
    fwrite(STDERR, "BimModel with id={$id} not found\n");
    exit(2);
}

$url = URL::temporarySignedRoute('bim.models.file.public', now()->addMinutes(15), ['id' => $m->id]);
echo $url . PHP_EOL;
