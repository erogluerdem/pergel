<?php

namespace App\Http\Controllers;

use App\Models\SubcontractorWorker;
use App\Models\SubcontractorWorkerDocument;
use App\Models\Subcontractor;
use App\Models\Project;
use Illuminate\Http\Request;

class SubcontractorWorkerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $workers = SubcontractorWorker::with(['subcontractor', 'project', 'documents'])->orderBy('created_at', 'desc')->paginate(15);
        $subcontractors = Subcontractor::pluck('company_name', 'id');
        $projects = Project::pluck('name', 'id');

        return view('subcontractor_workers.index', compact('workers', 'subcontractors', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subcontractor_id' => 'required|exists:subcontractors,id',
            'project_id' => 'nullable|exists:projects,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'tc_no' => 'nullable|string',
            'job_title' => 'nullable|string',
            'sgk_status' => 'required|in:active,pending,missing',
            'notes' => 'nullable|string',
        ]);

        $entryStatus = ($validated['sgk_status'] === 'active') ? 'approved' : 'blocked';
        $blockedReason = ($entryStatus === 'blocked') ? 'SGK Kaydı Aktif Değil veya Eksik' : null;

        SubcontractorWorker::create(array_merge($validated, [
            'entry_permit_status' => $entryStatus,
            'blocked_reason' => $blockedReason,
        ]));

        return redirect()->route('subcontractor_workers.index')->with('success', 'Taşeron çalışanı kaydı eklendi.');
    }

    public function addDocument(Request $request, SubcontractorWorker $worker)
    {
        $validated = $request->validate([
            'document_type' => 'required|in:sgk_entry,isg_certificate,health_report_ek2,work_at_height,vocational_cert,other',
            'file' => 'nullable|file|max:10240',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('worker_docs', 'public');
        }

        $isValid = true;
        if (!empty($validated['expiry_date']) && $validated['expiry_date'] < now()->toDateString()) {
            $isValid = false;
        }

        $worker->documents()->create([
            'document_type' => $validated['document_type'],
            'file_path' => $path,
            'issue_date' => $validated['issue_date'] ?? null,
            'expiry_date' => $validated['expiry_date'] ?? null,
            'is_valid' => $isValid,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Evrak geçersiz veya eksikse giriş iznini güncelle
        if (!$isValid) {
            $worker->update([
                'entry_permit_status' => 'blocked',
                'blocked_reason' => 'Evrak Süresi Dolmuş: ' . $validated['document_type'],
            ]);
        }

        return redirect()->route('subcontractor_workers.index')->with('success', 'Taşeron çalışanına İSG evrakı eklendi.');
    }

    public function destroy(SubcontractorWorker $worker)
    {
        $worker->delete();
        return redirect()->route('subcontractor_workers.index')->with('success', 'Taşeron çalışan kaydı silindi.');
    }
}
