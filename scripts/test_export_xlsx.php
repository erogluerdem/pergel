<?php
// Quick test runner: call FinanceController::reportExport for XLSX and save output to file
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
// Create a request for type=receivable_ageing&format=xlsx
$request = Illuminate\Http\Request::create('/finance/accounts/reports/export', 'GET', ['type' => 'receivable_ageing', 'format' => 'xlsx']);
// Set some server vars
$request->server->set('HTTP_HOST', '127.0.0.1');
// Handle via kernel to get full response
$response = $kernel->handle($request);
// Save response content if any
$file = __DIR__ . '/tmp_export.xlsx';
file_put_contents($file, $response->getContent());
echo "Wrote: $file\n";
$kernel->terminate($request, $response);
