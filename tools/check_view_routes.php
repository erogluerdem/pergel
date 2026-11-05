<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$router = $app->make('router');
$defined = [];
foreach ($router->getRoutes() as $r) {
    $name = $r->getName();
    if ($name) $defined[$name] = true;
}

$dir = __DIR__ . '/../resources/views';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$used = [];
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    if (!preg_match('/\.blade\.php$/', $file->getFilename())) continue;
    $lines = file($file->getPathname());
    foreach ($lines as $ln => $content) {
        if (strpos($content, 'route(') === false && strpos($content, 'route("') === false) continue;
        if (preg_match_all('/route\(\s*(["\'])([^"\']+)\1/', $content, $m)) {
            foreach ($m[2] as $match) {
                $used[$match][] = $file->getPathname() . ':' . ($ln+1);
            }
        }
    }
}

$missing = [];
foreach ($used as $name => $occ) {
    if (!isset($defined[$name])) {
        $missing[$name] = $occ;
    }
}

if (empty($missing)) {
    echo "OK: No missing route names found in views.\n";
    exit(0);
}

foreach ($missing as $name => $occs) {
    echo "MISSING: $name\n";
    foreach ($occs as $o) echo "  - $o\n";
}

exit(2);
