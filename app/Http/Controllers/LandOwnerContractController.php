<?php

namespace App\Http\Controllers;

use App\Models\LandOwnerContract;
use App\Models\UnitShareMatrix;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Http\Request;

class LandOwnerContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $contracts = LandOwnerContract::with(['project', 'unitShareMatrices.unit'])->paginate(15);
        $projects = Project::pluck('name', 'id');
        $units = Unit::all();

        return view('land_owner_contracts.index', compact('contracts', 'projects', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'land_owner_name' => 'required|string',
            'land_owner_tc_vkn' => 'nullable|string',
            'land_share_percentage' => 'required|numeric|min:0|max:100',
            'contractor_share_percentage' => 'required|numeric|min:0|max:100',
            'deed_transfer_terms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $contract = LandOwnerContract::create($validated);

        return redirect()->route('land_owner_contracts.index')->with('success', 'Kat karşılığı arsa sahibi sözleşmesi eklendi.');
    }

    public function addUnitMatrix(Request $request, LandOwnerContract $contract)
    {
        $validated = $request->validate([
            'unit_id' => 'nullable|exists:units,id',
            'owner_type' => 'required|in:land_owner,contractor,shared',
            'stage_requirement' => 'required|in:substructure,superstructure,finishing,occupancy_permit',
            'is_deed_transferred' => 'boolean',
            'deed_transfer_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $contract->unitShareMatrices()->create($validated);

        return redirect()->route('land_owner_contracts.index')->with('success', 'Ünite paylaşım matrisi kaydedildi.');
    }

    public function destroy(LandOwnerContract $contract)
    {
        $contract->delete();
        return redirect()->route('land_owner_contracts.index')->with('success', 'Kat karşılığı sözleşmesi silindi.');
    }
}
