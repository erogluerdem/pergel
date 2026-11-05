<?php

namespace App\Http\Controllers;

use App\Models\SalesTarget;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesTargetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Tüm hedefleri listele
    public function index()
    {
        $user = auth()->user();
        
        // Yönetici ise tüm hedefleri göster, normal kullanıcı sadece kendi hedeflerini
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            $targets = SalesTarget::with(['user'])->orderBy('period_start', 'desc')->paginate(15);
        } else {
            $targets = SalesTarget::where('user_id', $user->id)->orderBy('period_start', 'desc')->paginate(15);
        }

        return view('crm.sales-targets.index', compact('targets'));
    }

    // Hedef oluşturma formu
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('crm.sales-targets.create', compact('users'));
    }

    // Hedef kaydet
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'target_type' => 'required|in:revenue,unit',
            'target_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ], [
            'user_id.required' => 'Kullanıcı seçimi zorunludur',
            'period_start.required' => 'Başlangıç tarihi zorunludur',
            'period_end.required' => 'Bitiş tarihi zorunludur',
            'target_amount.required' => 'Hedef miktar zorunludur',
        ]);

        SalesTarget::create($validated);

        return redirect()->route('sales-targets.index')->with('success', 'Satış hedefi başarıyla oluşturuldu');
    }

    // Hedef detayını göster
    public function show(SalesTarget $salesTarget)
    {
        // Yönetici veya kendinin hedefi ise göster
        if (auth()->user()->id !== $salesTarget->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        // Hedeflenen dönem içinde gerçekleşen satışları getir
        $completedOpportunities = $salesTarget->user->opportunities()
            ->where('stage', 'won')
            ->whereBetween('updated_at', [$salesTarget->period_start, $salesTarget->period_end])
            ->get();

        return view('crm.sales-targets.show', compact('salesTarget', 'completedOpportunities'));
    }

    // Hedef düzenleme formu
    public function edit(SalesTarget $salesTarget)
    {
        if (auth()->user()->id !== $salesTarget->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $users = User::orderBy('name')->get();
        return view('crm.sales-targets.edit', compact('salesTarget', 'users'));
    }

    // Hedef güncelle
    public function update(Request $request, SalesTarget $salesTarget)
    {
        if (auth()->user()->id !== $salesTarget->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'target_type' => 'required|in:revenue,unit',
            'target_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,abandoned'
        ]);

        $salesTarget->update($validated);

        return redirect()->route('sales-targets.show', $salesTarget)->with('success', 'Satış hedefi başarıyla güncellendi');
    }

    // Hedef sil
    public function destroy(SalesTarget $salesTarget)
    {
        if (auth()->user()->id !== $salesTarget->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $salesTarget->delete();

        return redirect()->route('sales-targets.index')->with('success', 'Satış hedefi başarıyla silindi');
    }

    // Dashboard / Özet görünümü - Tüm hedeflerin özeti
    public function dashboard()
    {
        $user = auth()->user();
        
        // Cari ay hedefleri
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            $activeTargets = SalesTarget::where('status', 'active')
                ->whereBetween('period_start', [$currentMonthStart, $currentMonthEnd])
                ->get();

            $allUsers = User::with('salesTargets')->get();
        } else {
            $activeTargets = $user->salesTargets()
                ->where('status', 'active')
                ->whereBetween('period_start', [$currentMonthStart, $currentMonthEnd])
                ->get();

            $allUsers = collect([$user]);
        }

        return view('crm.sales-targets.dashboard', compact('activeTargets', 'allUsers'));
    }
}
