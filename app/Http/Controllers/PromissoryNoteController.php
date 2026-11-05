<?php

namespace App\Http\Controllers;

use App\Models\PromissoryNote;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Http\Request;

class PromissoryNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = PromissoryNote::with(['customer', 'project', 'unit']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $notes = $query->orderBy('due_date', 'asc')->paginate(15);
        $customers = Customer::pluck('name', 'id');
        $projects = Project::pluck('name', 'id');
        $units = Unit::all();

        $stats = [
            'total_portfolio' => PromissoryNote::where('status', 'portfolio')->sum('amount'),
            'total_collected' => PromissoryNote::where('status', 'collected')->sum('amount'),
            'total_endorsed' => PromissoryNote::where('status', 'endorsed')->sum('amount'),
            'total_protested' => PromissoryNote::where('status', 'protested')->sum('amount'),
        ];

        return view('promissory_notes.index', compact('notes', 'customers', 'projects', 'units', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'project_id' => 'nullable|exists:projects,id',
            'unit_id' => 'nullable|exists:units,id',
            'note_number' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'debtor_name' => 'required|string',
            'debtor_tc_vkn' => 'nullable|string',
            'guarantor_name' => 'nullable|string',
            'status' => 'required|in:portfolio,collected,endorsed,protested,legal_action',
            'endorsed_to_supplier' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        PromissoryNote::create($validated);

        return redirect()->route('promissory_notes.index')->with('success', 'Müşteri senet kaydı başarıyla oluşturuldu.');
    }

    public function updateStatus(Request $request, PromissoryNote $note)
    {
        $validated = $request->validate([
            'status' => 'required|in:portfolio,collected,endorsed,protested,legal_action',
            'endorsed_to_supplier' => 'nullable|string',
        ]);

        $note->update($validated);

        return redirect()->route('promissory_notes.index')->with('success', 'Senet durumu güncellendi.');
    }

    public function destroy(PromissoryNote $note)
    {
        $note->delete();
        return redirect()->route('promissory_notes.index')->with('success', 'Senet kaydı silindi.');
    }
}
