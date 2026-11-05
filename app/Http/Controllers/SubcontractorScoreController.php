<?php

namespace App\Http\Controllers;

use App\Models\SubcontractorScore;
use App\Models\Subcontractor;
use Illuminate\Http\Request;

class SubcontractorScoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = SubcontractorScore::orderBy('total_score', 'desc')->paginate(15);
        $subcontractors = Subcontractor::pluck('company_name', 'id');

        $stats = [
            'avg_score' => round(SubcontractorScore::avg('total_score') ?? 0, 1),
            'grade_a_count' => SubcontractorScore::whereIn('grade', ['A+', 'A'])->count(),
            'grade_c_count' => SubcontractorScore::where('grade', 'C')->count(),
            'total_subcontractors' => SubcontractorScore::count(),
        ];

        return view('subcontractor_scores.index', compact('items', 'subcontractors', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subcontractor_name' => 'required|string',
            'ai_reliability_score' => 'required|integer|min:0|max:100',
            'safety_score' => 'required|integer|min:0|max:100',
            'speed_score' => 'required|integer|min:0|max:100',
            'system_comment' => 'nullable|string',
        ]);

        $rel = intval($validated['ai_reliability_score']);
        $safe = intval($validated['safety_score']);
        $speed = intval($validated['speed_score']);

        $totalScore = round(($rel * 0.4) + ($safe * 0.3) + ($speed * 0.3));

        if ($totalScore >= 90) {
            $grade = 'A+';
            $defaultComment = "🏆 Mükemmel Performans: Tüm İSG, hız ve kalite standartlarını aşmaktadır.";
        } elseif ($totalScore >= 80) {
            $grade = 'A';
            $defaultComment = "🟢 Yüksek Performans: Şantiye taahhütlerini zamanında ve kaliteli tamamlıyor.";
        } elseif ($totalScore >= 70) {
            $grade = 'B+';
            $defaultComment = "🟡 Standart Uyum: Genel imalat kalitesi iyi, İSG kontrolleri artırılabilir.";
        } elseif ($totalScore >= 60) {
            $grade = 'B';
            $defaultComment = "🟧 Gelişime Açık: İş teslimlerinde kısmi gecikmeler mevcut.";
        } else {
            $grade = 'C';
            $defaultComment = "🔴 Yüksek Risk: İSG ve iş kalitesi standart altında. Sözleşme fesih riski.";
        }

        SubcontractorScore::create([
            'subcontractor_name' => $validated['subcontractor_name'],
            'ai_reliability_score' => $rel,
            'safety_score' => $safe,
            'speed_score' => $speed,
            'total_score' => $totalScore,
            'grade' => $grade,
            'system_comment' => $validated['system_comment'] ?: $defaultComment,
        ]);

        return back()->with('success', 'Taşeron Performans Karnesi başarıyla oluşturuldu.');
    }

    public function update(Request $request, SubcontractorScore $subcontractorScore)
    {
        $validated = $request->validate([
            'ai_reliability_score' => 'required|integer|min:0|max:100',
            'safety_score' => 'required|integer|min:0|max:100',
            'speed_score' => 'required|integer|min:0|max:100',
        ]);

        $rel = intval($validated['ai_reliability_score']);
        $safe = intval($validated['safety_score']);
        $speed = intval($validated['speed_score']);

        $totalScore = round(($rel * 0.4) + ($safe * 0.3) + ($speed * 0.3));
        $grade = $totalScore >= 90 ? 'A+' : ($totalScore >= 80 ? 'A' : ($totalScore >= 70 ? 'B+' : ($totalScore >= 60 ? 'B' : 'C')));

        $subcontractorScore->update([
            'ai_reliability_score' => $rel,
            'safety_score' => $safe,
            'speed_score' => $speed,
            'total_score' => $totalScore,
            'grade' => $grade,
        ]);

        return back()->with('success', 'Taşeron karne puanları güncellendi.');
    }

    public function destroy(SubcontractorScore $subcontractorScore)
    {
        $subcontractorScore->delete();
        return back()->with('success', 'Taşeron karne kaydı silindi.');
    }
}
