<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Controllers\ProjectController;

$id = 5;
// Ensure project exists
$project = Project::find($id);
if (!$project) {
    $project = Project::create(['name' => 'Auto Test Project #' . $id]);
    $id = $project->id;
    echo "Created project with id: $id\n";
}

// Build request data (minimal fields from form)
$data = [
    'name' => 'Test Update ' . time(),
    'project_code' => 'TST' . rand(100,999),
    'budget' => '12345.67',
    'progress_percent' => '25',
    'spent' => '1000.00',
    'location_province' => 'İstanbul',
    'location_district' => 'Kadıköy',
    // add other fields as needed
];

// Create Request instance simulating PUT (form-data)
$request = Request::create('/projects/' . $id, 'POST', $data);
// emulate method override used by Blade when using @method('PUT')
$request->setMethod('PUT');

$controller = new ProjectController();

try {
    $response = $controller->update($request, $id);
    // If the controller redirects, $response may be RedirectResponse
    if (is_object($response)) {
        if (method_exists($response, 'getStatusCode')) {
            echo "Response status: " . $response->getStatusCode() . "\n";
        }
        if (method_exists($response, 'getContent')) {
            echo "Response content: " . $response->getContent() . "\n";
        }
        if (method_exists($response, 'getTargetUrl')) {
            echo "Redirect to: " . $response->getTargetUrl() . "\n";
        }
    } else {
        echo "Controller returned: "; var_export($response); echo "\n";
    }
} catch (\Throwable $e) {
    echo "EXCEPTION: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
