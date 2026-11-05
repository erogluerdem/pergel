<?php

namespace App\Http\Controllers\SubcontractorPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubcontractorWorkerDocument;

class WorkerDocumentController extends Controller
{
    public function index()
    {
        $subcontractorId = Auth::guard('subcontractor')->id() ?? auth()->user()?->subcontractor_id ?? 1;
        $documents = SubcontractorWorkerDocument::where('subcontractor_id', $subcontractorId)->get();
        return view('subcontractor-portal.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('subcontractor-portal.documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'worker_id' => 'required',
            'document_type' => 'required',
            'file_path' => 'required', // Should be file upload in real scenario
        ]);

        $subcontractorId = Auth::guard('subcontractor')->id() ?? auth()->user()?->subcontractor_id ?? 1;

        SubcontractorWorkerDocument::create([
            'subcontractor_id' => $subcontractorId,
            'worker_id' => $request->worker_id,
            'document_type' => $request->document_type,
            'file_path' => $request->file_path,
            'is_valid' => true,
        ]);

        return redirect()->route('subcontractor.documents.index')->with('success', 'Belge başarıyla yüklendi ve onaya gönderildi.');
    }
}
