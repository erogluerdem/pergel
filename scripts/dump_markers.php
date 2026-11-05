<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Models\Project;

$projects = Project::all();
$markers = [];
foreach ($projects as $p) {
    $loc = $p->location ?? null;
    $lat = null; $lng = null;
    if (is_array($loc)) {
        $lat = $loc['lat'] ?? null; $lng = $loc['lng'] ?? null;
    } elseif (is_object($loc)) {
        $lat = $loc->lat ?? null; $lng = $loc->lng ?? null;
    }
    if ($lat !== null && $lng !== null) {
        $markers[] = [
            'id' => $p->id,
            'name' => $p->name,
            'lat' => $lat,
            'lng' => $lng,
            'budget' => $p->budget ?? 0,
            'progress_percent' => $p->progress_percent ?? 0,
        ];
    }
}

echo json_encode($markers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
