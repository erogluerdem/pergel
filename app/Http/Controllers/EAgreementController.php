<?php

namespace App\Http\Controllers;

use App\Models\EAgreement;
use Illuminate\Http\Request;

class EAgreementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = EAgreement::orderBy('period_month', 'desc')->paginate(15);

        $stats = [
            'total_our_balance' => EAgreement::sum('our_balance'),
            'agreed_count' => EAgreement::where('status', 'mutabik_kalindi')->count(),
            'conflict_count' => EAgreement::where('status', 'uyusmazlik')->count(),
            'pending_count' => EAgreement::where('status', 'gonderildi')->count(),
            'total_agreements' => EAgreement::count(),
        ];

        return view('e_agreements.index', compact('items', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'partner_name' => 'required|string',
            'partner_tax_no' => 'required|string',
            'period_month' => 'required|string',
            'our_balance' => 'required|numeric',
            'partner_balance' => 'nullable|numeric',
            'mutabakat_type' => 'required|string',
            'status' => 'required|in:gonderildi,mutabik_kalindi,uyusmazlik',
        ]);

        EAgreement::create($validated);

        return back()->with('success', 'E-Mutabakat mektubu başarıyla oluşturuldu.');
    }

    public function update(Request $request, EAgreement $eAgreement)
    {
        $validated = $request->validate([
            'partner_balance' => 'nullable|numeric',
            'status' => 'required|in:gonderildi,mutabik_kalindi,uyusmazlik',
        ]);

        $eAgreement->update($validated);

        return back()->with('success', 'E-Mutabakat yanıtı ve durumu güncellendi.');
    }

    public function destroy(EAgreement $eAgreement)
    {
        $eAgreement->delete();
        return back()->with('success', 'E-Mutabakat kaydı silindi.');
    }
}
