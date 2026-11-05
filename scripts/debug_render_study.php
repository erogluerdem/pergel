<?php
// Quick debug helper: render feasibility show view for given id and dump basic markers
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput(),
    new Symfony\Component\Console\Output\NullOutput()
);

// Bootstrapped. Now resolve controller and call show.
use App\Http\Controllers\FeasibilityController;
use Illuminate\Http\Request;

$id = $argv[1] ?? 1;
$controller = new FeasibilityController();
$response = $controller->show($id);
// If it's a View, get rendered content
if ($response instanceof Illuminate\Contracts\View\View) {
    echo "[VIEW RENDERED]\n";
    echo (string) $response->render();
} else {
    echo "Response class: " . get_class($response) . "\n";
    if (method_exists($response, 'getContent')) echo $response->getContent();
}

$kernel->terminate($input, $status);
