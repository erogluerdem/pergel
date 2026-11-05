<?php

require __DIR__ . '/../vendor/autoload.php';

// Bootstrap the framework
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ContractTemplate;
use App\Models\ContractClause;

echo "ContractTemplates:\n";
$templates = ContractTemplate::orderBy('id','desc')->limit(10)->get();
foreach ($templates as $t) {
    echo "- [{$t->id}] {$t->title}\n";
}

echo "\nContractClauses:\n";
$clauses = ContractClause::orderBy('id','desc')->limit(20)->get();
foreach ($clauses as $c) {
    echo "- [{$c->id}] {$c->title}\n";
}

echo "\nDone.\n";
