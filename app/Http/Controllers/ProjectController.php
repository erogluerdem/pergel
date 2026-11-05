<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Document;
use App\Services\ProjectService;
use App\Services\DocumentService;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use Illuminate\Support\Facades\Schema;

class ProjectController extends Controller
{
    private ProjectService $projectService;
    private DocumentService $documentService;

    public function __construct(
        ProjectService $projectService,
        DocumentService $documentService
    ) {
        $this->projectService = $projectService;
        $this->documentService = $documentService;

        $this->middleware('auth')->only([
            'create', 'store', 'edit', 'update', 'destroy',
            'trash', 'restore', 'forceDelete'
        ]);
    }

    public function trashed()
    {
        $projects = Project::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(20);
        return view('portfolio.trashed-projects', compact('projects'));
    }

    public function trash($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return Redirect::back()->with('success', 'Proje çöpe atıldı.');
    }

    public function restore($id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->restore();
        return Redirect::back()->with('success', 'Proje geri alındı.');
    }

    public function forceDelete($id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);
        $project->forceDelete();
        return Redirect::back()->with('success', 'Proje kalıcı olarak silindi.');
    }

    public function index(Request $request)
    {
        $filters = [
            'q' => $request->query('q'),
            'status' => $request->query('status'),
            'type' => $request->query('type'),
            'manager' => $request->query('manager'),
            'region' => $request->query('region'),
            'health' => $request->query('health'),
        ];

        $projects = $this->projectService->getFilteredProjects($filters, perPage: 20);
        $summary = $this->projectService->getProjectsCollectionSummary(collect($projects->items()));

        $activeProjects = $summary['active_projects'] ?? $projects->total();
        $totalValue = $summary['total_budget'] ?? 0;
        $onTimeRate = 95;
        $budgetComplianceRate = round(100 - ($summary['budget_utilization'] ?? 0), 1);
        if ($budgetComplianceRate <= 0 || $budgetComplianceRate > 100) {
            $budgetComplianceRate = 92;
        }

        $statusOptions = [
            'planned' => 'Planlama',
            'in_progress' => 'Devam Ediyor',
            'completed' => 'Tamamlandı',
            'on_hold' => 'Beklemede',
            'cancelled' => 'İptal Edildi'
        ];

        $typeOptions = [
            'konut' => 'Konut Projesi',
            'ticari' => 'Ticari Proje',
            'karma' => 'Karma Proje',
            'altyapi' => 'Altyapı & Taahhüt',
            'endustriyel' => 'Endüstriyel'
        ];

        $healthOptions = [
            'healthy' => 'Sağlıklı',
            'risky' => 'Riskli',
            'critical' => 'Kritik'
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'data' => $projects->items(),
                'meta' => [
                    'current_page' => $projects->currentPage(),
                    'last_page' => $projects->lastPage(),
                    'total' => $projects->total(),
                ]
            ]);
        }

        return view('portfolio.all-projects', compact(
            'projects',
            'summary',
            'activeProjects',
            'totalValue',
            'onTimeRate',
            'budgetComplianceRate',
            'statusOptions',
            'typeOptions',
            'healthOptions'
        ));
    }

    public function create()
    {
        if (! app(\App\Services\PergelQuotaService::class)->canCreateProject()) {
            return redirect()
                ->route('admin.license.upgrade', ['reason' => 'project_quota'])
                ->with('warning', 'Ücretsiz Topluluk sürümünde maksimum 2 aktif şantiye/proje yönetebilirsiniz. Sınırsız şantiye için paketinizi yükseltin.');
        }

        $project = new Project();
        return view('portfolio.project-form', compact('project'));
    }

    public function store(ProjectStoreRequest $request)
    {
        if (! app(\App\Services\PergelQuotaService::class)->canCreateProject()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Ücretsiz Topluluk sürümünde maksimum 2 aktif şantiye yönetebilirsiniz. Sınırsız şantiye için paketinizi yükseltin.',
                    'upgrade_url' => route('admin.license.upgrade', ['reason' => 'project_quota']),
                ], 403);
            }

            return redirect()
                ->route('admin.license.upgrade', ['reason' => 'project_quota'])
                ->with('warning', 'Ücretsiz Topluluk sürümünde maksimum 2 aktif şantiye/proje yönetebilirsiniz. Sınırsız şantiye için paketinizi yükseltin.');
        }

        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $coverPath = $this->documentService->uploadFile($request->file('cover_image'), 'projects', 'covers');
            if ($coverPath) $data['cover_image'] = $coverPath;
        }

        if ($request->hasFile('attachments')) {
            $attachments = $this->documentService->uploadMultiple($request->file('attachments'), 'projects');
            if (!empty($attachments)) $data['attachments'] = $attachments;
        }

        $project = Project::create($data);
        $this->storeDocumentUploads($request, $project);

        return redirect()->route('projects.show', $project->id)->with('success', 'Proje oluşturuldu.');       
    }

    public function show($id)
    {
        $project = Project::with('documents')->findOrFail($id);
        $summary = $this->projectService->getProjectSummary($project);
        return view('portfolio.project-detail', compact('project', 'summary'));
    }

    public function apiShow($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($this->projectService->getProjectSummary($project));
    }

    public function apiGetVillas($id)
    {
        $project = Project::findOrFail($id);
        $villas = $project->villas()->select('id', 'name', 'unit_number', 'status', 'area', 'price')->get();        
        return response()->json(['villas' => $villas]);
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $budgetUtilization = $this->projectService->calculateBudgetUtilization($project);
        return view('portfolio.project-form', compact('project', 'budgetUtilization'));
    }

    public function update(ProjectUpdateRequest $request, $id)
    {
        $project = Project::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($project->cover_image) $this->documentService->deleteFile($project->cover_image);
            $coverPath = $this->documentService->uploadFile($request->file('cover_image'), 'projects', 'covers');
            if ($coverPath) $data['cover_image'] = $coverPath;
        }

        $project->update($data);
        $this->storeDocumentUploads($request, $project);

        return redirect()->route('projects.show', $project->id)->with('success', 'Proje güncellendi.');       
    }

    public function destroy($id)
    {
        Project::findOrFail($id)->delete();
        return redirect()->route('projects.index')->with('success', 'Proje silindi.');
    }

    private function storeDocumentUploads(Request $request, Project $project): void
    {
        $documentTypes = [
            'building_permit_file' => 'building_permit',
            'contractor_license_file' => 'contractor_license',
            'insurance_certificate_file' => 'insurance_certificate',
            'municipality_approval_file' => 'municipality_approval'
        ];
        
        foreach ($documentTypes as $inputName => $docType) {
            if ($request->hasFile($inputName)) {
                $filePath = $this->documentService->uploadFile($request->file($inputName), 'projects', $project->id);
                if ($filePath) {
                    Document::create([
                        'project_id' => $project->id,
                        'name' => $request->file($inputName)->getClientOriginalName(),
                        'file_path' => $filePath,
                        'mime_type' => $request->file($inputName)->getMimeType(),
                        'uploaded_by' => auth()->id(),
                        'uploaded_at' => now(),
                        'metadata' => ['type' => $docType],
                    ]);
                }
            }
        }

        if ($request->hasFile('other_documents')) {
            foreach ($request->file('other_documents') as $file) {
                $filePath = $this->documentService->uploadFile($file, 'projects', $project->id);
                if ($filePath) {
                    Document::create([
                        'project_id' => $project->id,
                        'name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'mime_type' => $file->getMimeType(),
                        'uploaded_by' => auth()->id(),
                        'uploaded_at' => now(),
                        'metadata' => ['type' => 'other'],
                    ]);
                }
            }
        }
    }
}
