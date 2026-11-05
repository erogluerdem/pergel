<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Asset;
use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Opsiyon listesi
    public function index()
    {
        $options = Option::with(['asset', 'customer', 'opportunity'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('crm.options.index', compact('options'));
    }

    // Yeni Opsiyon formu
    public function create()
    {
        $assets = Asset::where('status', 'active')->orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        $opportunities = Opportunity::orderBy('title')->get();

        return view('crm.options.create', compact('assets', 'customers', 'opportunities'));
    }

    // Opsiyon kaydet
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'customer_id' => 'required|exists:customers,id',
            'opportunity_id' => 'nullable|exists:opportunities,id',
            'duration_days' => 'required|integer|min:1|max:365',
            'notes' => 'nullable|string',
        ], [
            'asset_id.required' => 'Gayrimenkul seçimi zorunludur',
            'customer_id.required' => 'Müşteri seçimi zorunludur',
            'duration_days.required' => 'Opsiyon süresi zorunludur',
        ]);

        $optionDate = now();
        $expiryDate = now()->addDays($validated['duration_days']);

        $option = Option::create(array_merge($validated, [
            'option_date' => $optionDate,
            'expiry_date' => $expiryDate,
            'status' => 'active'
        ]));

        return redirect()->route('options.show', $option)->with('success', 
            "Opsiyon başarıyla oluşturuldu. Bitme tarihi: {$expiryDate->format('d.m.Y')}");
    }

    // Opsiyon detay
    public function show(Option $option)
    {
        return view('crm.options.show', compact('option'));
    }

    // Opsiyon düzenleme formu
    public function edit(Option $option)
    {
        if ($option->status !== 'active') {
            abort(403, 'Aktif olmayan opsiyonlar düzenlenemez.');
        }

        $assets = Asset::where('available', true)->orWhere('id', $option->asset_id)->orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        $opportunities = Opportunity::orderBy('title')->get();

        return view('crm.options.edit', compact('option', 'assets', 'customers', 'opportunities'));
    }

    // Opsiyon güncelle
    public function update(Request $request, Option $option)
    {
        if ($option->status !== 'active') {
            abort(403, 'Aktif olmayan opsiyonlar güncellenemez.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'opportunity_id' => 'nullable|exists:opportunities,id',
            'notes' => 'nullable|string',
        ]);

        $option->update($validated);

        return redirect()->route('options.show', $option)->with('success', 'Opsiyon başarıyla güncellendi');
    }

    // Opsiyonu Projeye Dönüştür
    public function convertToProject(Option $option)
    {
        if ($option->status !== 'active') {
            abort(403, 'Sadece aktif opsiyonlar projeye dönüştürülebilir.');
        }

        $option->load(['asset', 'customer', 'opportunity']);

        $projectName = $option->title
            ?? $option->name
            ?? $option->opportunity?->title
            ?? ($option->asset ? $option->asset->name . ' Projesi' : 'Opsiyon Projesi #' . $option->id);

        $budget = $option->amount
            ?? $option->estimated_value
            ?? $option->opportunity?->estimated_value
            ?? $option->asset?->current_value
            ?? $option->asset?->purchase_price
            ?? 0;

        $project = Project::create([
            'name' => $projectName,
            'code' => 'PRJ-' . strtoupper(Str::random(5)),
            'status' => 'planning',
            'budget' => $budget,
            'description' => $option->description ?? $option->notes ?? $option->opportunity?->description,
            'client_name' => $option->customer?->name ?? $option->opportunity?->customer,
            'start_date' => now(),
        ]);

        if ($option->opportunity) {
            $option->opportunity->update(['stage' => 'won', 'probability' => 100]);
        }

        $option->update(['status' => 'converted_to_project']);

        if ($option->opportunity_id) {
            return redirect()->route('opportunities.show', $option->opportunity_id)
                ->with('success', 'Opsiyon başarıyla projeye dönüştürüldü (Proje: ' . $project->name . ')');
        }

        return redirect()->route('options.show', $option)
            ->with('success', 'Opsiyon başarıyla projeye dönüştürüldü (Proje: ' . $project->name . ')');
    }

    // Opsiyonu İptal Et
    public function cancel(Option $option)
    {
        if ($option->status !== 'active') {
            abort(403, 'Sadece aktif opsiyonlar iptal edilebilir.');
        }

        $option->update(['status' => 'cancelled']);

        return back()->with('success', 'Opsiyon iptal edilmiştir');
    }

    // Süresi dolan opsiyonları otomatik işle
    public static function expireOldOptions()
    {
        Option::expireOldOptions();
    }

    // Süresi dolmak üzere olan opsiyonları göster (Dashboard uyarısı)
    public function aboutToExpire(Request $request)
    {
        $days = (int) $request->input('days', 1);
        $expiringOptions = Option::where('status', 'active')
            ->whereBetween('expiry_date', [now()->toDateString(), now()->addDays($days)->toDateString()])
            ->with(['asset', 'customer'])
            ->get()
            ->map(function ($option) {
                $option->days_remaining = $option->expiry_date ? max(0, now()->startOfDay()->diffInDays($option->expiry_date->startOfDay(), false)) : 0;
                return $option;
            });

        return view('crm.options.about-to-expire', compact('expiringOptions'));
    }

    // Opsiyon sil
    public function destroy(Option $option)
    {
        if ($option->status === 'converted_to_project') {
            abort(403, 'Projeye dönüştürülen opsiyonlar silinemez.');
        }

        $option->delete();

        return redirect()->route('options.index')->with('success', 'Opsiyon başarıyla silindi');
    }
}
