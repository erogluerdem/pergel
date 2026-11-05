<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StandardsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the standards.
     */
    public function index(Request $request)
    {
        $query = Standard::with(['creator', 'updater']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        // Filter by priority
        if ($request->has('priority') && !empty($request->priority)) {
            $query->where('priority', $request->priority);
        }

        // Filter by expiry status
        if ($request->has('expiry_filter')) {
            switch ($request->expiry_filter) {
                case 'expired':
                    $query->expired();
                    break;
                case 'expiring_soon':
                    $query->expiringSoon();
                    break;
                case 'no_expiry':
                    $query->whereNull('expiry_date');
                    break;
            }
        }

        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        
        if (in_array($sort, ['name', 'code', 'type', 'status', 'category', 'priority', 'effective_date', 'expiry_date', 'created_at'])) {
            $query->orderBy($sort, $direction);
        }

        $standards = $query->paginate(15);

        // Statistics for dashboard cards
        $stats = [
            'total' => Standard::count(),
            'active' => Standard::where('status', 'active')->count(),
            'expired' => Standard::expired()->count(),
            'expiring_soon' => Standard::expiringSoon()->count(),
            'draft' => Standard::where('status', 'draft')->count(),
            'critical' => Standard::where('priority', 1)->count(),
            'by_category' => Standard::selectRaw('category, COUNT(*) as count')
                                   ->groupBy('category')
                                   ->pluck('count', 'category')
                                   ->toArray(),
            'by_type' => Standard::selectRaw('type, COUNT(*) as count')
                                ->groupBy('type')
                                ->pluck('count', 'type')
                                ->toArray()
        ];

        if ($request->ajax()) {
            return response()->json([
                'standards' => $standards,
                'stats' => $stats
            ]);
        }

        return view('admin.standards.index', compact('standards', 'stats'));
    }

    /**
     * Show the form for creating a new standard.
     */
    public function create()
    {
        return view('admin.standards.create');
    }

    /**
     * Store a newly created standard in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:standards,code',
            'description' => 'nullable|string',
            'type' => 'required|in:' . implode(',', array_keys(Standard::TYPES)),
            'status' => 'required|in:' . implode(',', array_keys(Standard::STATUSES)),
            'version' => 'nullable|string|max:50',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:effective_date',
            'category' => 'required|in:' . implode(',', array_keys(Standard::CATEGORIES)),
            'priority' => 'required|integer|between:1,5',
            'compliance_level' => 'required|in:' . implode(',', array_keys(Standard::COMPLIANCE_LEVELS)),
            'document_url' => 'nullable|url',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $validator->validated();
            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();

            $standard = Standard::create($data);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Standart başarıyla oluşturuldu.',
                    'standard' => $standard->load(['creator', 'updater'])
                ]);
            }

            return redirect()->route('admin.standards.index')
                           ->with('success', 'Standart başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Standart oluşturulurken bir hata oluştu.'
                ], 500);
            }

            return redirect()->back()
                           ->with('error', 'Standart oluşturulurken bir hata oluştu.')
                           ->withInput();
        }
    }

    /**
     * Display the specified standard.
     */
    public function show(Standard $standard)
    {
        $standard->load(['creator', 'updater']);
        
        if (request()->ajax()) {
            return response()->json([
                'standard' => $standard
            ]);
        }

        return view('admin.standards.show', compact('standard'));
    }

    /**
     * Show the form for editing the specified standard.
     */
    public function edit(Standard $standard)
    {
        return view('admin.standards.edit', compact('standard'));
    }

    /**
     * Update the specified standard in storage.
     */
    public function update(Request $request, Standard $standard)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:standards,code,' . $standard->id,
            'description' => 'nullable|string',
            'type' => 'required|in:' . implode(',', array_keys(Standard::TYPES)),
            'status' => 'required|in:' . implode(',', array_keys(Standard::STATUSES)),
            'version' => 'nullable|string|max:50',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:effective_date',
            'category' => 'required|in:' . implode(',', array_keys(Standard::CATEGORIES)),
            'priority' => 'required|integer|between:1,5',
            'compliance_level' => 'required|in:' . implode(',', array_keys(Standard::COMPLIANCE_LEVELS)),
            'document_url' => 'nullable|url',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $validator->validated();
            $data['updated_by'] = auth()->id();

            $standard->update($data);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Standart başarıyla güncellendi.',
                    'standard' => $standard->fresh()->load(['creator', 'updater'])
                ]);
            }

            return redirect()->route('admin.standards.index')
                           ->with('success', 'Standart başarıyla güncellendi.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Standart güncellenirken bir hata oluştu.'
                ], 500);
            }

            return redirect()->back()
                           ->with('error', 'Standart güncellenirken bir hata oluştu.')
                           ->withInput();
        }
    }

    /**
     * Remove the specified standard from storage.
     */
    public function destroy(Standard $standard)
    {
        try {
            $standard->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Standart başarıyla silindi.'
                ]);
            }

            return redirect()->route('admin.standards.index')
                           ->with('success', 'Standart başarıyla silindi.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Standart silinirken bir hata oluştu.'
                ], 500);
            }

            return redirect()->back()
                           ->with('error', 'Standart silinirken bir hata oluştu.');
        }
    }

    /**
     * Toggle standard status.
     */
    public function toggleStatus(Request $request, Standard $standard)
    {
        try {
            $newStatus = $standard->status === 'active' ? 'inactive' : 'active';
            $standard->update([
                'status' => $newStatus,
                'updated_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Standart durumu güncellendi.',
                'standard' => $standard->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Durum güncellenirken bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * Bulk actions for multiple standards.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:delete,activate,deactivate,mark_expired',
            'standards' => 'required|array|min:1',
            'standards.*' => 'exists:standards,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $standards = Standard::whereIn('id', $request->standards);
            $count = $standards->count();

            switch ($request->action) {
                case 'delete':
                    $standards->delete();
                    $message = "{$count} standart başarıyla silindi.";
                    break;
                case 'activate':
                    $standards->update(['status' => 'active', 'updated_by' => auth()->id()]);
                    $message = "{$count} standart aktif duruma getirildi.";
                    break;
                case 'deactivate':
                    $standards->update(['status' => 'inactive', 'updated_by' => auth()->id()]);
                    $message = "{$count} standart pasif duruma getirildi.";
                    break;
                case 'mark_expired':
                    $standards->update(['status' => 'expired', 'updated_by' => auth()->id()]);
                    $message = "{$count} standart süresi dolmuş olarak işaretlendi.";
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Toplu işlem yapılırken bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * Export standards to CSV.
     */
    public function exportCsv(Request $request)
    {
        try {
            $query = Standard::with(['creator', 'updater']);

            // Apply same filters as index method
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            if ($request->has('type') && !empty($request->type)) {
                $query->where('type', $request->type);
            }

            if ($request->has('category') && !empty($request->category)) {
                $query->where('category', $request->category);
            }

            $standards = $query->orderBy('created_at', 'desc')->get();

            $filename = 'standards_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($standards) {
                $file = fopen('php://output', 'w');
                
                // UTF-8 BOM for proper encoding in Excel
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Headers
                fputcsv($file, [
                    'ID',
                    'Ad',
                    'Kod',
                    'Açıklama',
                    'Tür',
                    'Durum',
                    'Versiyon',
                    'Geçerlilik Tarihi',
                    'Son Geçerlilik Tarihi',
                    'Kategori',
                    'Öncelik',
                    'Uyumluluk Seviyesi',
                    'Doküman URL\'si',
                    'Etiketler',
                    'Oluşturan',
                    'Güncelleyen',
                    'Oluşturulma Tarihi',
                    'Güncellenme Tarihi'
                ]);

                // Data
                foreach ($standards as $standard) {
                    fputcsv($file, [
                        $standard->id,
                        $standard->name,
                        $standard->code,
                        $standard->description,
                        Standard::TYPES[$standard->type] ?? $standard->type,
                        Standard::STATUSES[$standard->status] ?? $standard->status,
                        $standard->version,
                        $standard->effective_date?->format('d.m.Y'),
                        $standard->expiry_date?->format('d.m.Y'),
                        Standard::CATEGORIES[$standard->category] ?? $standard->category,
                        Standard::PRIORITIES[$standard->priority] ?? $standard->priority,
                        Standard::COMPLIANCE_LEVELS[$standard->compliance_level] ?? $standard->compliance_level,
                        $standard->document_url,
                        $standard->tags ? implode(', ', $standard->tags) : '',
                        $standard->creator?->name,
                        $standard->updater?->name,
                        $standard->created_at->format('d.m.Y H:i'),
                        $standard->updated_at->format('d.m.Y H:i')
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'CSV dışa aktarma işleminde hata oluştu.'
            ], 500);
        }
    }
}