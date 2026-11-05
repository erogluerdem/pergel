<?php

namespace App\Http\Controllers;

use App\Models\DynamicReport;
use Illuminate\Http\Request;

class DynamicReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = DynamicReport::orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total_reports' => DynamicReport::count(),
            'pdf_count' => DynamicReport::where('file_format', 'PDF')->count(),
            'excel_count' => DynamicReport::where('file_format', 'EXCEL')->count(),
        ];

        return view('dynamic_reports.index', compact('items', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_title' => 'required|string',
            'report_type' => 'required|string',
            'generated_by' => 'required|string',
            'file_format' => 'required|in:PDF,EXCEL',
            'file_url' => 'nullable|string',
        ]);

        DynamicReport::create($validated);

        return back()->with('success', 'Dinamik rapor başarıyla oluşturuldu ve arşive eklendi.');
    }

    public function destroy(DynamicReport $dynamicReport)
    {
        $dynamicReport->delete();
        return back()->with('success', 'Dinamik rapor silindi.');
    }
}
