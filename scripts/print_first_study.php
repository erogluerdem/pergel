<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArrayInput([]),
    new Symfony\Component\Console\Output\NullOutput
);

// now we have app
use App\Models\FeasibilityStudy;
$first = FeasibilityStudy::orderBy('id')->first();
echo $first ? $first->id : 'NONE';

$kernel->terminate($input, $status);
