<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;
use App\Models\Vendor;

$project = Project::first();
if (! $project) {
    $project = Project::create(['name' => 'Örnek Proje']);
    echo "Created sample project: {$project->id}\n";
}

$vendor = Vendor::first();
if ($vendor) {
    $vendor->projects()->syncWithoutDetaching([$project->id]);
    echo "Linked vendor {$vendor->id} to project {$project->id}\n";
} else {
    echo "No vendor found to link.\n";
}
