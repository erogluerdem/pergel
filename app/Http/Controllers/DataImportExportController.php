<?php

namespace App\Http\Controllers;

use App\Models\DataImport;
use App\Services\DataParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DataImportExportController extends Controller
{
    protected DataParser $parser;

    public function __construct(DataParser $parser)
    {
        $this->parser = $parser;
        $this->middleware('role:admin,manager', ['except' => ['index', 'show', 'exportForm']]);
    }

    /**
     * Import/Export Dashboard
     */
    public function index()
    {
        $imports = DataImport::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => DataImport::count(),
            'completed' => DataImport::where('status', 'completed')->count(),
            'failed' => DataImport::where('status', 'failed')->count(),
            'processing' => DataImport::where('status', 'processing')->count(),
        ];

        return view('data_import_export.index', compact('imports', 'stats'));
    }

    /**
     * Import formu göster
     */
    public function importForm()
    {
        $modules = [
            'invoices' => 'Faturalar',
            'projects' => 'Projeler',
            'users' => 'Kullanıcılar',
            'contacts' => 'Kontaklar',
            'tasks' => 'Görevler',
        ];

        return view('data_import_export.import_form', compact('modules'));
    }

    /**
     * File upload ve preview
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx',
            'module' => 'required|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('imports', 'local');

        // Parse dosya
        $data = $this->parser->parseExcel(storage_path("app/{$path}"));

        if ($data->isEmpty()) {
            return back()->with('error', 'Dosya boş veya okundu olamıyor');
        }

        // Preview (ilk 5 satır)
        $preview = $data->take(5);
        $total = $data->count();

        return view('data_import_export.preview', compact('preview', 'total', 'path', 'module'));
    }

    /**
     * Import işlemini başlat
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,json|max:10240',
            'module' => 'required|string',
        ]);

        $file = $request->file('file');
        $module = $request->input('module');
        $storagePath = $file->store('imports', 'local');

        try {
            // Parse dosya
            $extension = $file->getClientOriginalExtension();
            $data = match($extension) {
                'xlsx' => $this->parser->parseExcel(storage_path("app/{$storagePath}")),
                'csv' => $this->parser->parseCSV(storage_path("app/{$storagePath}")),
                'json' => $this->parser->parseJSON(storage_path("app/{$storagePath}")),
                default => throw new \Exception("Desteklenmeyen dosya formatı"),
            };

            if ($data->isEmpty()) {
                return back()->with('error', 'Dosya boş veya okundu olamıyor');
            }

            // DataImport kaydı oluştur
            $import = DataImport::create([
                'user_id' => auth()->id(),
                'module' => $module,
                'status' => 'pending',
                'file_path' => $storagePath,
                'file_name' => $file->getClientOriginalName(),
                'total_rows' => $data->count(),
            ]);

            // Queue job'u dispatch et
            \App\Jobs\ProcessDataImport::dispatch($import, $data->toArray())
                ->onConnection('database')
                ->onQueue('default');

            return redirect()->route('data-import-export.show', $import)
                ->with('success', 'İçe aktarım işlemi başlatıldı. Lütfen durumu kontrol ediniz.');
        } catch (\Exception $e) {
            return back()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Export formu
     */
    public function exportForm()
    {
        $modules = [
            'invoices' => 'Faturalar',
            'projects' => 'Projeler',
            'users' => 'Kullanıcılar',
            'contacts' => 'Kontaklar',
            'tasks' => 'Görevler',
        ];

        return view('data_import_export.export_form', compact('modules'));
    }

    /**
     * Verileri dışa aktar
     */
    public function export(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
            'format' => 'required|in:csv,excel',
        ]);

        $module = $request->input('module');
        $format = $request->input('format');
        $data = $this->getModuleData($module);

        if (empty($data)) {
            return back()->with('error', 'Dışa aktarılacak veri yok');
        }

        $fileName = "{$module}-" . now()->format('Y-m-d-His');
        
        if ($format === 'csv') {
            $path = $this->exportToCSV($data, $fileName);
            return response()->download($path)->deleteFileAfterSend(true);
        } else {
            $path = $this->exportToExcel($data, $fileName);
            return response()->download($path)->deleteFileAfterSend(true);
        }
    }

    /**
     * Import detaylarını göster
     */
    public function show(DataImport $import)
    {
        $this->authorize('view', $import);
        return view('data_import_export.show', compact('import'));
    }

    /**
     * Import loglarını göster
     */
    public function logs()
    {
        $logs = DataImport::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('data_import_export.logs', compact('logs'));
    }

    /**
     * AJAX - Import durumunu kontrol et
     */
    public function importStatus(DataImport $import)
    {
        $this->authorize('view', $import);

        return response()->json([
            'status' => $import->status,
            'total_rows' => $import->total_rows,
            'imported_rows' => $import->imported_rows,
            'failed_rows' => $import->failed_rows,
            'percentage' => $import->total_rows > 0 
                ? round(($import->imported_rows / $import->total_rows) * 100) 
                : 0,
            'errors' => $import->getErrors(),
        ]);
    }

    /**
     * Hata raporunu indir
     */
    public function downloadErrorReport(DataImport $import)
    {
        $this->authorize('view', $import);

        if ($import->failed_rows === 0) {
            return back()->with('info', 'Bu işlemde hata bulunmamaktadır');
        }

        $fileName = "error-report-{$import->id}-" . now()->format('Y-m-d-His') . ".csv";
        $path = storage_path("app/exports/{$fileName}");

        $errors = $import->getErrors();
        
        if (!is_dir(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        $file = fopen($path, 'w');
        fputcsv($file, ['Satır No', 'Hata Mesajı']);

        foreach ($errors as $error) {
            fputcsv($file, [
                $error['row'],
                $error['message'],
            ]);
        }

        fclose($file);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    /**
     * İçe aktarımı sil
     */
    public function destroy(DataImport $import)
    {
        $this->authorize('delete', $import);

        $import->delete();

        return back()->with('success', 'İçe aktarım silindi');
    }

    // ============ HELPERS ============

    private function getValidationRules(string $module): array
    {
        return match($module) {
            'invoices' => [
                'invoice_number' => 'required',
                'amount' => 'required|numeric',
                'date' => 'required|date',
            ],
            'projects' => [
                'name' => 'required',
                'budget' => 'required|numeric',
            ],
            'users' => [
                'name' => 'required',
                'email' => 'required|email',
            ],
            default => [],
        };
    }

    private function getModuleData(string $module): array
    {
        return match($module) {
            'invoices' => \App\Models\Invoice::select('invoice_number', 'amount', 'date', 'status')->get()->toArray(),
            'projects' => \App\Models\Project::select('name', 'budget', 'status', 'start_date')->get()->toArray(),
            'users' => \App\Models\User::select('name', 'email', 'role')->get()->toArray(),
            'contacts' => collect(),
            'tasks' => collect(),
            default => [],
        };
    }

    private function exportToCSV(array $data, string $fileName): string
    {
        if (!is_dir(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        $path = storage_path("app/exports/{$fileName}.csv");
        $file = fopen($path, 'w');

        if (!empty($data)) {
            // Header'ı yaz
            $headers = array_keys((array)$data[0]);
            fputcsv($file, $headers);

            // Data'yı yaz
            foreach ($data as $row) {
                fputcsv($file, (array)$row);
            }
        }

        fclose($file);
        return $path;
    }

    private function exportToExcel(array $data, string $fileName): string
    {
        if (!is_dir(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            if (!empty($data)) {
                // Header'ı yaz
                $headers = array_keys((array)$data[0]);
                foreach ($headers as $col => $header) {
                    $sheet->setCellValueByColumnAndRow($col + 1, 1, $header);
                }

                // Data'yı yaz
                foreach ($data as $row => $item) {
                    foreach ((array)$item as $col => $value) {
                        $sheet->setCellValueByColumnAndRow(array_search($col, array_keys((array)$item)) + 1, $row + 2, $value);
                    }
                }
            }

            $path = storage_path("app/exports/{$fileName}.xlsx");
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($path);

            return $path;
        } catch (\Exception $e) {
            // Fallback to CSV if Excel fails
            return $this->exportToCSV($data, $fileName);
        }
    }
}
