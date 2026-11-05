<?php

namespace App\Http\Controllers;

use App\Models\PropertyDocument;
use App\Models\Asset;
use App\Models\Project;
use Illuminate\Http\Request;

class PropertyDocumentController extends Controller
{
    /**
     * Tapu ve mesken belgeleri listesi
     */
    public function index(Request $request)
    {
        $documents = PropertyDocument::with(['asset', 'project'])
            ->when($request->filled('type'), fn($q) => $q->where('document_type', $request->type))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('project_id'), fn($q) => $q->where('project_id', $request->project_id))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $types = [
            'tapu' => 'Tapu Belgesi',
            'mesken' => 'Mesken Belgesi',
            'imar' => 'İmar Durum Belgesi',
            'yapı_ruhsat' => 'Yapı Ruhsatı',
            'yapı_kullanma' => 'Yapı Kullanma İzni',
            'elektrik' => 'Elektrik Tesisatı',
            'su' => 'Su Tesisatı',
            'doğalgaz' => 'Doğalgaz Tesisatı',
        ];

        $statuses = ['pending' => 'Bekleme', 'received' => 'Alındı', 'processed' => 'İşlendi', 'expired' => 'Süresi Doldu'];
        $projects = Project::orderBy('name')->get();

        return view('property_documents.index', compact('documents', 'types', 'statuses', 'projects'));
    }

    /**
     * Belge oluştur
     */
    public function create(Request $request)
    {
        $assets = Asset::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('property_documents.create', compact('assets', 'projects'));
    }

    /**
     * Belgeyi kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'nullable|exists:assets,id',
            'project_id' => 'nullable|exists:projects,id',
            'document_type' => 'required|in:tapu,mesken,imar,yapı_ruhsat,yapı_kullanma,elektrik,su,doğalgaz',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp,dwg,zip|max:20480', // 10MB
            'document_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:pending,received,processed,expired',
            'issued_by' => 'nullable|string|max:255',
            'document_cost' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('property_documents', 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
        }

        PropertyDocument::create($validated);

        return redirect()->route('property_documents.index')
            ->with('success', 'Belge başarıyla kaydedildi!');
    }

    /**
     * Belge detaylarını göster
     */
    public function show($id)
    {
        $document = PropertyDocument::with(['asset', 'project'])->findOrFail($id);
        return view('property_documents.show', compact('document'));
    }

    /**
     * Belgeyi düzenle
     */
    public function edit($id)
    {
        $document = PropertyDocument::findOrFail($id);
        $assets = Asset::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('property_documents.edit', compact('document', 'assets', 'projects'));
    }

    /**
     * Belgeyi güncelle
     */
    public function update(Request $request, $id)
    {
        $document = PropertyDocument::findOrFail($id);

        $validated = $request->validate([
            'asset_id' => 'nullable|exists:assets,id',
            'project_id' => 'nullable|exists:projects,id',
            'document_type' => 'required|in:tapu,mesken,imar,yapı_ruhsat,yapı_kullanma,elektrik,su,doğalgaz',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp,dwg,zip|max:20480',
            'document_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:pending,received,processed,expired',
            'issued_by' => 'nullable|string|max:255',
            'document_cost' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('property_documents', 'public');
            $validated['file_path'] = $path;
            $validated['file_name'] = $file->getClientOriginalName();
        }

        $document->update($validated);

        return redirect()->route('property_documents.show', $document->id)
            ->with('success', 'Belge başarıyla güncellendi!');
    }

    /**
     * Belgeyi sil
     */
    public function destroy($id)
    {
        $document = PropertyDocument::findOrFail($id);
        $document->delete();

        return redirect()->route('property_documents.index')
            ->with('success', 'Belge silinmiştir.');
    }
}
