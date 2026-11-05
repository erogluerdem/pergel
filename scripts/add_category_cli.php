<?php
// Adds a category to storage/app/expense_categories.json (similar logic to controller)
$name = $argv[1] ?? 'CLI Test Kategori ' . time();
$file = __DIR__ . '/../storage/app/expense_categories.json';
$categories = [];
if (file_exists($file)) {
    $categories = json_decode(file_get_contents($file), true) ?: [];
}
$key = strtolower(preg_replace('/[^a-z0-9]+/i', '_', trim($name)));
$origKey = $key; $i = 1;
while (isset($categories[$key])) { $key = $origKey . '_' . $i; $i++; }
$categories[$key] = $name;
try {
    $written = @file_put_contents($file, json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    if ($written === false) {
        echo "WRITE_FAILED\n";
        exit(2);
    }
    echo "OK|$key\n";
} catch (Exception $e) {
    echo 'EXCEPTION: '. $e->getMessage() . "\n";
    exit(3);
}
