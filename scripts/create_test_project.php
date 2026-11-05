<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $p = App\Models\Project::create(['name'=>'Test SQL '.time(), 'project_code'=>'TST'.time()]);
    echo 'CREATED: '.$p->id."\n";
} catch (\Throwable $e) {
    echo 'ERROR: '.$e->getMessage()."\n";
}
