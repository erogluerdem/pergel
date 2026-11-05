<?php

namespace App\Http\Controllers;

use App\Models\FleetTelemetry;
use Illuminate\Http\Request;

class FleetTelemetryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = FleetTelemetry::orderBy('record_date', 'desc')->paginate(15);

        $operatingSum = FleetTelemetry::sum('operating_hours');
        $idleSum = FleetTelemetry::sum('idle_hours');
        $fuelSum = FleetTelemetry::sum('fuel_consumed_liters');
        $totalHours = $operatingSum + $idleSum;
        $efficiency = $totalHours > 0 ? ($operatingSum / $totalHours) * 100 : 0;

        $stats = [
            'total_operating_hours' => $operatingSum,
            'total_idle_hours' => $idleSum,
            'total_fuel_liters' => $fuelSum,
            'efficiency_rate' => $efficiency,
            'vehicle_count' => FleetTelemetry::distinct('vehicle_plate')->count('vehicle_plate'),
            'total_records' => FleetTelemetry::count(),
        ];

        return view('fleet_telemetries.index', compact('items', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_plate' => 'required|string',
            'vehicle_type' => 'required|string',
            'record_date' => 'required|date',
            'operating_hours' => 'required|numeric|min:0',
            'idle_hours' => 'required|numeric|min:0',
            'fuel_consumed_liters' => 'required|numeric|min:0',
            'operator_name' => 'nullable|string',
        ]);

        FleetTelemetry::create($validated);

        return back()->with('success', 'IoT Araç Telemetri kaydı başarıyla oluşturuldu.');
    }

    public function update(Request $request, FleetTelemetry $fleetTelemetry)
    {
        $validated = $request->validate([
            'operating_hours' => 'required|numeric|min:0',
            'idle_hours' => 'required|numeric|min:0',
            'fuel_consumed_liters' => 'required|numeric|min:0',
        ]);

        $fleetTelemetry->update($validated);

        return back()->with('success', 'Telemetri kaydı güncellendi.');
    }

    public function destroy(FleetTelemetry $fleetTelemetry)
    {
        $fleetTelemetry->delete();
        return back()->with('success', 'Telemetri kaydı silindi.');
    }
}
