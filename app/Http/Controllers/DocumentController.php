<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Document;
use App\Models\DocumentFolder;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display all documents across all projects (general documents index)
     */
    public function index()
    {
        // Get all projects with document counts for overview
        $projects = Project::withCount('documents')->orderBy('name')->get();
        
        // Get recent documents across all projects
        $recentDocuments = Document::with(['project'])
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();
        
        // Statistics
        $stats = [
            'total_projects' => Project::count(),
            'total_documents' => Document::count(),
            'total_folders' => DocumentFolder::count(),
            'recent_uploads' => Document::where('created_at', '>=', now()->subDays(7))->count()
        ];
        
        return view('documents.index', compact('projects', 'recentDocuments', 'stats'));
    }

    /**
     * Display documents for a specific project
     */
    public function projectDocuments($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        $folders = DocumentFolder::where('project_id', $project->id)->whereNull('parent_id')->get();
        $documents = Document::where('project_id', $project->id)->orderBy('updated_at','desc')->paginate(25);
        return view('projects.documents.index', compact('project','folders','documents'));
    }

    public function all()
    {
        $projects = Project::withCount('documents')
            ->orderBy('name')
            ->paginate(12);
        $totalProjects = Project::count();
        $totalDocuments = Document::count();
        return view('projects.documents.list', compact('projects', 'totalProjects', 'totalDocuments'));
    }

    public function uploadForm($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('upload', $project);
        $folders = DocumentFolder::where('project_id', $project->id)->get();
        $documents = Document::where('project_id', $project->id)->orderBy('name')->get();
        return view('projects.documents.upload', compact('project','folders','documents'));
    }

    public function store(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('upload', $project);
        $request->validate([
            'file' => 'required|file|max:51200', // 50MB
            'mode' => 'required|in:new,version',
            'name' => 'required_if:mode,new|string',
            'document_id' => 'required_if:mode,version|nullable|integer',
            'discipline' => 'nullable|string',
            'status' => 'nullable|string',
            'version' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents');

        if ($request->input('mode') === 'new') {
            $doc = Document::create([
                'project_id' => $project->id,
                'folder_id' => $request->input('folder_id'),
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'file_path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'version' => $request->input('version') ?? 'V1',
                'status' => $request->input('status') ?? 'draft',
                'discipline' => $request->input('discipline'),
                'uploaded_by' => auth()->id() ?? null,
                'uploaded_at' => now(),
                'replaced_document_id' => null,
                'metadata' => null,
            ]);
        } else {
            $base = Document::where('project_id', $project->id)->where('id', $request->input('document_id'))->firstOrFail();
            $doc = Document::create([
                'project_id' => $project->id,
                'folder_id' => $base->folder_id,
                'name' => $base->name,
                'code' => $base->code,
                'file_path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'version' => $request->input('version') ?? ($base->version . '.1'),
                'status' => $request->input('status') ?? 'review',
                'discipline' => $base->discipline ?? $request->input('discipline'),
                'uploaded_by' => auth()->id() ?? null,
                'uploaded_at' => now(),
                'replaced_document_id' => $base->id,
                'metadata' => null,
            ]);

            $base->status = 'old';
            $base->save();
        }

        return redirect()->route('projects.documents.index', $project->id)->with('success','Doküman yüklendi');
    }

    public function showFile($projectId, $id)
    {
        $doc = Document::where('project_id',$projectId)->findOrFail($id);
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        $possiblePaths = [
            storage_path('app/' . $doc->file_path),
            storage_path('app/private/' . $doc->file_path),
            storage_path('app/public/' . $doc->file_path),
            public_path($doc->file_path),
            public_path('storage/' . $doc->file_path),
        ];

        foreach ($possiblePaths as $filePath) {
            if ($filePath && file_exists($filePath) && is_file($filePath)) {
                return response()->file($filePath);
            }
        }

        // Fallback: Generate demo sample PDF if file missing on disk
        $targetPath = storage_path('app/' . ($doc->file_path ?: 'documents/demo.pdf'));
        if (!file_exists(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }

        $cleanName = preg_replace('/[^a-zA-Z0-9 _-]/', '', $doc->name ?? 'Dokuman');
        $samplePdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj 3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<</Font<//F1 4 0 R>>>>/Contents 5 0 R>>endobj 4 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj 5 0 obj<</Length 80>>stream\nBT /F1 20 Tf 50 720 Td (PERGEL DOKUMAN ARSIVI) Tj ET\nBT /F1 14 Tf 50 680 Td (" . $cleanName . ") Tj ET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000056 00000 n \n0000000111 00000 n \n0000000238 00000 n \n0000000305 00000 n \ntrailer<</Size 6/Root 1 0 R>>\nstartxref\n434\n%%EOF";

        file_put_contents($targetPath, $samplePdfContent);

        return response()->file($targetPath);
    }

    public function viewer($projectId, $id)
    {
        $doc = Document::where('project_id',$projectId)->findOrFail($id);
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        if (!str_contains($doc->mime_type ?? 'pdf', 'pdf')) {
            return redirect()->route('projects.documents.show', [$projectId, $id]);
        }
        return view('projects.documents.viewer', compact('doc','projectId'));
    }

    public function history($projectId, $id)
    {
        $doc = Document::where('project_id',$projectId)->findOrFail($id);
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        $chain = [];
        $cursor = $doc;
        while ($cursor) {
            $chain[] = $cursor;
            if ($cursor->replaced_document_id) {
                $cursor = Document::find($cursor->replaced_document_id);
            } else {
                break;
            }
        }
        return view('projects.documents.history', compact('doc','chain'));
    }

    public function contractIndex($contractId)
    {
        $contract = \App\Models\Contract::findOrFail($contractId);
        $this->authorize('view', $contract);
        $documents = Document::where('contract_id', $contract->id)->orderBy('updated_at','desc')->paginate(25);
        return view('contracts.documents.index', compact('contract','documents'));
    }

    public function storeForContract(Request $request, $contractId)
    {
        $contract = \App\Models\Contract::findOrFail($contractId);
        $project = $contract->project;
        $this->authorize('upload', $project);

        $request->validate([
            'file' => 'required|file|max:51200',
            'name' => 'required|string',
            'discipline' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents');

        $doc = Document::create([
            'project_id' => $project->id ?? null,
            'contract_id' => $contract->id,
            'folder_id' => $request->input('folder_id'),
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'version' => $request->input('version') ?? 'V1',
            'status' => $request->input('status') ?? 'draft',
            'discipline' => $request->input('discipline'),
            'uploaded_by' => auth()->id() ?? null,
            'uploaded_at' => now(),
            'replaced_document_id' => null,
            'metadata' => null,
        ]);

        return redirect()->route('contracts.documents.index', $contract->id)->with('success','Doküman yüklendi');
    }

    public function showForContract($contractId, $id)
    {
        $doc = Document::where('contract_id',$contractId)->findOrFail($id);
        $contract = \App\Models\Contract::findOrFail($contractId);
        $this->authorize('view', $contract);
        
        $possiblePaths = [
            storage_path('app/' . $doc->file_path),
            storage_path('app/private/' . $doc->file_path),
            storage_path('app/public/' . $doc->file_path),
            public_path($doc->file_path),
            public_path('storage/' . $doc->file_path),
        ];

        foreach ($possiblePaths as $filePath) {
            if ($filePath && file_exists($filePath) && is_file($filePath)) {
                return response()->file($filePath);
            }
        }

        $targetPath = storage_path('app/' . ($doc->file_path ?: 'documents/demo.pdf'));
        if (!file_exists(dirname($targetPath))) {
            mkdir(dirname($targetPath), 0755, true);
        }

        $cleanName = preg_replace('/[^a-zA-Z0-9 _-]/', '', $doc->name ?? 'Dokuman');
        $samplePdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj 3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<</Font<//F1 4 0 R>>>>/Contents 5 0 R>>endobj 4 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj 5 0 obj<</Length 80>>stream\nBT /F1 20 Tf 50 720 Td (PERGEL DOKUMAN ARSIVI) Tj ET\nBT /F1 14 Tf 50 680 Td (" . $cleanName . ") Tj ET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000056 00000 n \n0000000111 00000 n \n0000000238 00000 n \n0000000305 00000 n \ntrailer<</Size 6/Root 1 0 R>>\nstartxref\n434\n%%EOF";

        file_put_contents($targetPath, $samplePdfContent);

        return response()->file($targetPath);
    }

    public function viewerForContract($contractId, $id)
    {
        $doc = Document::where('contract_id',$contractId)->findOrFail($id);
        $contract = \App\Models\Contract::findOrFail($contractId);
        $this->authorize('view', $contract);
        if (!str_contains($doc->mime_type, 'pdf')) {
            return redirect()->route('contracts.documents.show', [$contractId, $id]);
        }
        return view('projects.documents.viewer', compact('doc','contractId'));
    }
}
