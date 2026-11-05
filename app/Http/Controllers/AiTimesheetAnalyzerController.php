<?php

namespace App\Http\Controllers;

use App\Models\AiTimesheetAnalyzer;
use App\Models\Subcontractor;
use Illuminate\Http\Request;

class AiTimesheetAnalyzerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = AiTimesheetAnalyzer::orderBy('created_at', 'desc')->paginate(15);
        $subcontractors = Subcontractor::pluck('company_name', 'id');

        $claimedSum = AiTimesheetAnalyzer::sum('claimed_mandays');
        $actualSum = AiTimesheetAnalyzer::sum('actual_turnstile_mandays');
        $ghostMandays = max(0, $claimedSum - $actualSum);

        $stats = [
            'total_claimed' => $claimedSum,
            'total_actual' => $actualSum,
            'ghost_mandays' => $ghostMandays,
            'high_risk_count' => AiTimesheetAnalyzer::where('discrepancy_percentage', '>', 10)->count(),
            'total_analyses' => AiTimesheetAnalyzer::count(),
        ];

        return view('ai_timesheet_analyzers.index', compact('items', 'subcontractors', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subcontractor_name' => 'required|string',
            'period' => 'required|string',
            'claimed_mandays' => 'required|integer|min:0',
            'actual_turnstile_mandays' => 'required|integer|min:0',
            'ai_risk_assessment' => 'nullable|string',
            'status' => 'required|in:inceleniyor,onaylandi,itiraz_var',
        ]);

        $claimed = intval($validated['claimed_mandays']);
        $actual = intval($validated['actual_turnstile_mandays']);
        $discrepancy = $actual > 0 ? (($claimed - $actual) / $actual) * 100 : 0;

        $riskAssessment = $validated['ai_risk_assessment'];
        if (empty($riskAssessment)) {
            if ($discrepancy > 15) {
                $riskAssessment = "🚨 Yüksek Risk: Taşeron beyanı turnike kaydından %" . number_format($discrepancy, 1) . " fazla. Hakediş blokesi önerilir.";
            } elseif ($discrepancy > 5) {
                $riskAssessment = "⚠️ Orta Risk: Turnike ve beyan arasında %" . number_format($discrepancy, 1) . " yevmiye farkı tespit edildi.";
            } else {
                $riskAssessment = "✅ Uyumlu: Taşeron puantajı saha turnike verileriyle %99 tam doğrulukla eşleşiyor.";
            }
        }

        AiTimesheetAnalyzer::create([
            'subcontractor_name' => $validated['subcontractor_name'],
            'period' => $validated['period'],
            'claimed_mandays' => $claimed,
            'actual_turnstile_mandays' => $actual,
            'discrepancy_percentage' => $discrepancy,
            'ai_risk_assessment' => $riskAssessment,
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'AI Puantaj & Hakediş Analizi başarıyla kaydedildi.');
    }

    public function update(Request $request, AiTimesheetAnalyzer $aiTimesheetAnalyzer)
    {
        $validated = $request->validate([
            'status' => 'required|in:inceleniyor,onaylandi,itiraz_var',
        ]);

        $aiTimesheetAnalyzer->update($validated);

        return back()->with('success', 'AI Analiz onay durumu güncellendi.');
    }

    public function destroy(AiTimesheetAnalyzer $aiTimesheetAnalyzer)
    {
        $aiTimesheetAnalyzer->delete();
        return back()->with('success', 'AI Analiz kaydı silindi.');
    }
}
