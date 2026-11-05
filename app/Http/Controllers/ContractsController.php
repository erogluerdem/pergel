<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Project;
use App\Models\ContractTemplate;
use App\Models\ContractClause;
use Illuminate\Http\RedirectResponse;

class ContractsController extends Controller
{
    /**
     * Show the contract management page (dashboard + detail + library)
     */
    public function management(Request $request)
    {
    $query = Contract::query()->with(['project', 'payments']);

        // Filters: project, type, company, number, q (general)
        if ($request->filled('project')) {
            $query->where('project_id', $request->input('project'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('company')) {
            $query->where('company', 'like', '%' . $request->input('company') . '%');
        }

        if ($request->filled('number')) {
            $query->where('number', 'like', '%' . $request->input('number') . '%');
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('company', 'like', "%{$q}%")
                    ->orWhere('number', 'like', "%{$q}%");
            });
        }

        $contracts = $query->orderBy('end_date', 'asc')->paginate(25)->appends($request->query());

        // If caller expects JSON (AJAX live search), return minimal JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'data' => $contracts->items(),
                'meta' => [
                    'total' => $contracts->total(),
                    'per_page' => $contracts->perPage(),
                    'current_page' => $contracts->currentPage(),
                    'last_page' => $contracts->lastPage(),
                ]
            ]);
        }

        // fetch templates & clauses from DB so real data is used
        $templates = ContractTemplate::orderBy('title')->get();
        $clauses = ContractClause::orderBy('title')->get();

        $projects = Project::orderBy('name')->get();

        return view('contracts.management', compact('contracts', 'templates', 'clauses', 'projects'));
    }

    /**
     * Show a simple create form (used by modal fallback / dedicated page)
     */
    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $templates = ContractTemplate::orderBy('title')->get();
        $clauses = ContractClause::orderBy('title')->get();

        // If requested via AJAX (modal load), return the form partial only
        if (request()->ajax() || request()->wantsJson()) {
            return view('contracts._form', compact('projects','templates','clauses'));
        }

        return view('contracts.create', compact('projects','templates','clauses'));
    }

    /**
     * Store a minimal contract
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'nullable|string|max:100',
            'project_id' => 'nullable|exists:projects,id',
            'company' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'value' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'clauses' => 'nullable|array',
            'clauses.*' => 'integer|exists:contract_clauses,id',
        ]);

        // If clauses were selected, append their titles to notes for quick reference
        $notes = $data['notes'] ?? '';
        if ($request->filled('clauses')) {
            $clauseTitles = ContractClause::whereIn('id', $request->input('clauses'))->pluck('title')->toArray();
            if (!empty($clauseTitles)) {
                $notes .= "\n\nSeçilen Maddeler:\n" . implode("\n- ", $clauseTitles);
            }
        }

        $contract = Contract::create(array_merge($data, ['status' => 'draft', 'notes' => $notes]));

        // If a documents index exists for the contract, redirect there so user can upload docs
        if (\Illuminate\Support\Facades\Route::has('contracts.documents.index')) {
            return redirect()->route('contracts.documents.index', $contract)->with('success', 'Sözleşme oluşturuldu — dokümanları buradan yükleyebilirsiniz');
        }

        return redirect()->route('contracts.management')->with('success', 'Sözleşme oluşturuldu');
    }

    public function show($id)
    {
        $contract = Contract::with(['project', 'payments', 'documents', 'createdBy', 'template'])->findOrFail($id);
        return view('contracts.show', compact('contract'));
    }

    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        $projects = Project::orderBy('name')->get();
        $templates = ContractTemplate::orderBy('title')->get();
        $clauses = ContractClause::orderBy('title')->get();
        return view('contracts.edit', compact('contract', 'projects', 'templates', 'clauses'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $contract = Contract::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'nullable|string|max:100',
            'project_id' => 'nullable|exists:projects,id',
            'company' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'value' => 'nullable|numeric',
            'paid' => 'nullable|numeric',
            'status' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $contract->update($data);
        return redirect()->route('contracts.show', $contract->id)->with('success', 'Sözleşme başarıyla güncellendi');
    }

    public function destroy($id): RedirectResponse
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();
        return redirect()->route('contracts.management')->with('success', 'Sözleşme başarıyla silindi');
    }
}

