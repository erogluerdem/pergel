<?php

namespace App\Http\Controllers;

use App\Models\HrPersonnel;
use Illuminate\Http\Request;

class HrPersonnelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = HrPersonnel::orderBy('hire_date', 'desc')->paginate(15);

        $stats = [
            'total_personnel' => HrPersonnel::count(),
            'active_count' => HrPersonnel::where('status', 'aktif')->count(),
            'on_leave_count' => HrPersonnel::where('status', 'izinde')->count(),
            'total_monthly_payroll' => HrPersonnel::where('status', '!=', 'ayrildi')->sum('salary'),
        ];

        return view('hr_personnels.index', compact('items', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string',
            'position' => 'required|string',
            'hire_date' => 'required|date',
            'remaining_leaves' => 'required|integer|min:0',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,izinde,ayrildi',
        ]);

        HrPersonnel::create($validated);

        return back()->with('success', 'İnşaat İK & Özlük personel kaydı oluşturuldu.');
    }

    public function update(Request $request, HrPersonnel $hrPersonnel)
    {
        $validated = $request->validate([
            'position' => 'required|string',
            'remaining_leaves' => 'required|integer|min:0',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,izinde,ayrildi',
        ]);

        $hrPersonnel->update($validated);

        return back()->with('success', 'Personel özlük bilgileri güncellendi.');
    }

    public function destroy(HrPersonnel $hrPersonnel)
    {
        $hrPersonnel->delete();
        return back()->with('success', 'Personel kaydı silindi.');
    }
}
