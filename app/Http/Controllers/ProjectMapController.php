<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectMapController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        // Sample coordinates mapping for Turkish cities if project coordinates are empty
        $cityCoordinates = [
            'İstanbul' => ['lat' => 41.0082, 'lng' => 28.9784],
            'Ankara' => ['lat' => 39.9334, 'lng' => 32.8597],
            'İzmir' => ['lat' => 38.4237, 'lng' => 27.1428],
            'Bursa' => ['lat' => 40.1885, 'lng' => 29.0610],
            'Antalya' => ['lat' => 36.8969, 'lng' => 30.7133],
            'Kocaeli' => ['lat' => 40.8533, 'lng' => 29.8815],
            'Gaziantep' => ['lat' => 37.0662, 'lng' => 37.3833],
            'Konya' => ['lat' => 37.8746, 'lng' => 32.4932],
            'Adana' => ['lat' => 37.0000, 'lng' => 35.3213],
            'Muğla' => ['lat' => 37.2153, 'lng' => 28.3636]
        ];

        $mapData = $projects->map(function ($project, $index) use ($cityCoordinates) {
            $cityNames = array_keys($cityCoordinates);
            $cityName = $cityNames[$index % count($cityNames)];
            $baseCoords = $cityCoordinates[$cityName];

            // Slightly offset coordinates so markers in same city don't stack directly
            $lat = $project->latitude ?? ($baseCoords['lat'] + (($index % 5) * 0.012));
            $lng = $project->longitude ?? ($baseCoords['lng'] + (($index % 4) * 0.015));

            $status = $project->status ?? 'Devam Ediyor';
            $statusColor = '#2563eb'; // blue
            if (str_contains(strtolower($status), 'tamam')) $statusColor = '#10b981'; // green
            if (str_contains(strtolower($status), 'beton') || str_contains(strtolower($status), 'risk')) $statusColor = '#f59e0b'; // warning amber
            if (str_contains(strtolower($status), 'iptal') || str_contains(strtolower($status), 'dur')) $statusColor = '#ef4444'; // red

            return [
                'id' => $project->id,
                'name' => $project->name,
                'location' => $project->location ?? $cityName,
                'status' => $status,
                'status_color' => $statusColor,
                'progress' => rand(25, 95),
                'lat' => (float)$lat,
                'lng' => (float)$lng,
                'url' => route('projects.show', $project->id),
            ];
        });

        return view('projects.map', compact('mapData', 'projects'));
    }
}
