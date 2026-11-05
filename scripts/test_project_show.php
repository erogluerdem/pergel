<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\ProjectController;
use App\Models\Project;

$id = 7;
$project = Project::find($id);
if (!$project) {
    $project = Project::create(['name' => 'Show Test Project']);
    $id = $project->id;
    echo "Created project with id: $id\n";
}

$controller = new ProjectController();
try {
    $response = $controller->show($id);
    if (is_object($response)) {
        // Try common rendering methods safely
        if (method_exists($response, 'getContent')) {
            $content = $response->getContent();
            echo "OK: got response content length " . strlen($content) . "\n";
        } elseif (method_exists($response, 'render')) {
            $content = $response->render();
            echo "OK: rendered view length " . strlen($content) . "\n";
        } elseif (method_exists($response, '__toString')) {
            $content = (string) $response;
            echo "OK: string-casted response length " . strlen($content) . "\n";
        } else {
            echo "OK: controller returned object of class " . get_class($response) . "\n";
        }
    } else {
        echo "OK: controller returned: "; var_export($response); echo "\n";
    }
} catch (\Throwable $e) {
    echo "EXCEPTION: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
