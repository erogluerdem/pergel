<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$projects = App\Models\Project::all();
$html = view('portfolio.tenders.package-create', compact('projects'))->render();

$dir = __DIR__ . '/../public/preview';
if (!is_dir($dir)) mkdir($dir, 0755, true);
file_put_contents($dir . '/package-create.html', $html);
echo "Wrote preview to public/preview/package-create.html\n";
