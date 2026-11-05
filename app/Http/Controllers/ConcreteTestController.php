<?php

namespace App\Http\Controllers;

use App\Models\ConcreteTestRecord;
use App\Models\Project;
use Illuminate\Http\Request;

class ConcreteTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tests = ConcreteTestRecord::with('project')->orderBy('pouring_date', 'desc')->paginate(15);
        $projects = Project::pluck('name', 'id');

        return view('concrete_tests.index', compact('tests', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'pouring_date' => 'required|date',
            'concrete_class' => 'required|string',
            'location_element' => 'required|string',
            'quantity_m3' => 'required|numeric|min:0.1',
            'waybill_number' => 'nullable|string',
            'building_inspection_company' => 'nullable|string',
            'ebis_chip_numbers' => 'nullable|string',
            'day_7_strength_mpa' => 'nullable|numeric|min:0',
            'day_28_strength_mpa' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Status hesaplama (C30 için yaklaşık 30 MPa 28. gün hedefidir)
        $status = 'passed';
        $d28 = floatval($validated['day_28_strength_mpa'] ?? 0);
        $d7 = floatval($validated['day_7_strength_mpa'] ?? 0);

        if ($d28 > 0 && $d28 < 25.0) {
            $status = 'failed';
        } elseif ($d28 >= 25.0 && $d28 < 30.0) {
            $status = 'warning';
        } elseif ($d7 > 0 && $d7 < 15.0) {
            $status = 'warning';
        }

        $day7Target = $d7 > 0 ? ($d7 / 30.0) * 100 : null;
        $day28Target = $d28 > 0 ? ($d28 / 30.0) * 100 : null;

        ConcreteTestRecord::create(array_merge($validated, [
            'status' => $status,
            'day_7_target_percent' => $day7Target,
            'day_28_target_percent' => $day28Target,
        ]));

        return redirect()->route('concrete_tests.index')->with('success', 'EBİS Beton Kırım Test Kaydı eklendi.');
    }

    public function destroy(ConcreteTestRecord $test)
    {
        $test->delete();
        return redirect()->route('concrete_tests.index')->with('success', 'Beton testi kaydı silindi.');
    }
}
