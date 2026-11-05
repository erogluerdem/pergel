<?php
require __DIR__ . '/../vendor/autoload.php';
// bootstrap Laravel app
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Opportunity;
use App\Http\Controllers\OpportunityController;

// create a test opportunity in 'won' stage
$opp = Opportunity::create([
    'title' => 'Test Convert '.time(),
    'description' => 'Auto created',
    'customer' => 'Test Corp',
    'estimated_value' => 50000,
    'stage' => 'won'
]);

echo "Created opportunity id={$opp->id}\n";

$ctrl = new OpportunityController();
// simulate request
$request = new Illuminate\Http\Request();
$response = $ctrl->convertToProject($request, $opp->id);

if ($response instanceof Illuminate\Http\RedirectResponse) {
    echo "Redirected to: " . $response->getTargetUrl() . "\n";
} else {
    echo "Response type: " . get_class($response) . "\n";
}

// verify project created
$proj = App\Models\Project::where('name', $opp->title)->first();
if ($proj) echo "Project created id={$proj->id}\n"; else echo "Project not found\n";
