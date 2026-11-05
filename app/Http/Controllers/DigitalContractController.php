<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class DigitalContractController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager,legal', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $contracts = Contract::with('project', 'createdBy')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('digital_contracts.index', compact('contracts'));
    }

    public function create()
    {
        $projects = \App\Models\Project::pluck('name', 'id');
        $templates = \App\Models\ContractTemplate::where('status', 'active')->pluck('title', 'id');
        
        return view('digital_contracts.create', compact('projects', 'templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'template_id' => 'nullable|exists:contract_templates,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'effective_date' => 'required|date',
            'expiration_date' => 'nullable|date|after:effective_date',
            'status' => 'required|in:draft,active,expired,cancelled',
        ]);

        $validated['created_by'] = auth()->id();

        $contract = Contract::create($validated);
        
        return redirect()->route('digital-contracts.show', $contract)->with('success', 'Sözleşme oluşturuldu.');
    }

    public function show(Contract $contract)
    {
        $contract->load('project', 'createdBy', 'signatures');
        
        return view('digital_contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $projects = \App\Models\Project::pluck('name', 'id');
        $templates = \App\Models\ContractTemplate::where('status', 'active')->pluck('title', 'id');
        
        return view('digital_contracts.edit', compact('contract', 'projects', 'templates'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'effective_date' => 'required|date',
            'expiration_date' => 'nullable|date|after:effective_date',
            'status' => 'required|in:draft,active,expired,cancelled',
        ]);

        $contract->update($validated);
        
        return redirect()->route('digital-contracts.show', $contract)->with('success', 'Sözleşme güncellendi.');
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        
        return redirect()->route('digital-contracts.index')->with('success', 'Sözleşme silindi.');
    }

    public function generatePdf(Contract $contract)
    {
        return response()->streamDownload(function() use ($contract) {
            echo "<h1>" . e($contract->title ?? $contract->name) . "</h1><hr/>" . $contract->content;
        }, 'contract_' . $contract->id . '.html');
    }

    public function signatureRequest(Contract $contract, Request $request)
    {
        $validated = $request->validate([
            'signer_email' => 'required|email',
            'signer_name' => 'required|string|max:255',
            'signer_role' => 'required|string|max:100',
        ]);

        $signature = $contract->signatures()->create([
            'signer_email' => $validated['signer_email'],
            'signer_name' => $validated['signer_name'],
            'signer_role' => $validated['signer_role'],
            'status' => 'pending',
            'token' => \Str::random(60),
        ]);

        return redirect()->route('digital-contracts.show', $contract)->with('success', 'İmza talebi gönderildi.');
    }

    public function sign(Contract $contract, $token)
    {
        $signature = $contract->signatures()->where('token', $token)->firstOrFail();
        
        if ($signature->status !== 'pending') {
            return redirect()->route('root')->with('error', 'Bu imza talebinin geçerlilik süresi dolmuş.');
        }

        return view('digital_contracts.sign', compact('contract', 'signature'));
    }

    public function confirmSignature(Request $request, Contract $contract, $token)
    {
        $validated = $request->validate([
            'signature_image' => 'required|string',
        ]);

        $signature = $contract->signatures()->where('token', $token)->firstOrFail();

        $signature->update([
            'signature_data' => $validated['signature_image'],
            'signed_at' => now(),
            'status' => 'signed',
        ]);

        return redirect()->route('digital-contracts.show', $contract)->with('success', 'Sözleşme başarıyla imzalandı.');
    }
}
