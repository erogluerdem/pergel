<?php

namespace App\Http\Controllers;

use App\Models\TradeAsset;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Http\Request;

class TradeAssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $assets = TradeAsset::with(['customer', 'project', 'unit'])->orderBy('created_at', 'desc')->paginate(15);
        $customers = Customer::pluck('name', 'id');
        $projects = Project::pluck('name', 'id');
        $units = Unit::all();

        return view('trade_assets.index', compact('assets', 'customers', 'projects', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'project_id' => 'nullable|exists:projects,id',
            'unit_id' => 'nullable|exists:units,id',
            'asset_type' => 'required|in:vehicle,land,apartment,other',
            'title' => 'required|string',
            'valuation_amount' => 'required|numeric|min:0',
            'agreed_amount' => 'required|numeric|min:0',
            'status' => 'required|in:in_inventory,sold,transferred',
            'notes' => 'nullable|string',
        ]);

        TradeAsset::create($validated);

        return redirect()->route('trade_assets.index')->with('success', 'Takas varlığı kaydı başarıyla eklendi.');
    }

    public function destroy(TradeAsset $asset)
    {
        $asset->delete();
        return redirect()->route('trade_assets.index')->with('success', 'Takas varlığı silindi.');
    }
}
