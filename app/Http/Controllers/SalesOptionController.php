<?php

namespace App\Http\Controllers;

use App\Models\SalesOption;
use Illuminate\Http\Request;

class SalesOptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $options = SalesOption::orderBy('created_at', 'desc')->paginate(15);
        
        return view('sales_options.index', compact('options'));
    }

    public function create()
    {
        $statuses = ['draft', 'active', 'archived', 'expired'];
        
        return view('sales_options.create', compact('statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,active,archived,expired',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'extra_features' => 'nullable|string',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
        ]);

        $option = SalesOption::create($validated);
        
        return redirect()->route('sales_options.show', $option)->with('success', 'Opsiyon başarıyla oluşturuldu.');
    }

    public function show(SalesOption $salesOption)
    {
        return view('sales_options.show', compact('salesOption'));
    }

    public function edit(SalesOption $salesOption)
    {
        $this->authorize('update', $salesOption);
        $statuses = ['draft', 'active', 'archived', 'expired'];
        
        return view('sales_options.edit', compact('salesOption', 'statuses'));
    }

    public function update(Request $request, SalesOption $salesOption)
    {
        $this->authorize('update', $salesOption);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,active,archived,expired',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'extra_features' => 'nullable|string',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
        ]);

        $salesOption->update($validated);
        
        return redirect()->route('sales_options.show', $salesOption)->with('success', 'Opsiyon güncellendi.');
    }

    public function destroy(SalesOption $salesOption)
    {
        $this->authorize('delete', $salesOption);
        $salesOption->delete();
        
        return redirect()->route('sales_options.index')->with('success', 'Opsiyon silindi.');
    }
}
