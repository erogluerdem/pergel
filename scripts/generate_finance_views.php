<?php
// Usage: php scripts/generate_finance_views.php
// Scans FinanceController for view('finance....') calls and creates missing blade files with a placeholder.

$root = __DIR__ . '/..';
$controller = $root . '/app/Http/Controllers/FinanceController.php';
$viewsRoot = $root . '/resources/views';

if (!file_exists($controller)) {
    echo "FinanceController not found at $controller\n";
    exit(1);
}

$content = file_get_contents($controller);

preg_match_all("/return\s+view\(\s*'([^']+)'\s*\)/", $content, $m);
$views = array_unique($m[1] ?? []);

if (empty($views)) {
    echo "No finance views found in controller.\n";
    exit(0);
}

$created = [];
$exists = [];

foreach ($views as $view) {
    // normalize
    $path = $viewsRoot . '/' . str_replace('.', '/', $view) . '.blade.php';
    if (file_exists($path)) {
        $exists[] = $view;
        continue;
    }

    $dir = dirname($path);
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $title = ucfirst(array_slice(explode('.', $view), -1)[0]);
    $content = "@extends('layouts.master')\n";
    $content .= "@section('title', '{$title}')\n";
    $content .= "@section('topbar-title', 'Finans')\n\n";
    $content .= "@section('content')\n";
    $content .= "    <div class=\"card\"><div class=\"card-body\"><h4>{$title}</h4><p>Auto-generated placeholder for {$view}</p></div></div>\n";
    $content .= "@endsection\n";

    file_put_contents($path, $content);
    $created[] = $view;
}

echo "Summary:\n";
echo "Created: " . count($created) . "\n";
foreach ($created as $c) echo "  - $c\n";
echo "Exists: " . count($exists) . "\n";
foreach ($exists as $e) echo "  - $e\n";

echo "Done.\n";
