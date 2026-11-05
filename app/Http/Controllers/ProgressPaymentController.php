<?php

namespace App\Http\Controllers;

use App\Models\ProgressPayment;
use App\Models\ProgressPaymentDetail;
use App\Models\Project;
use App\Models\Contract;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProgressPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager', ['except' => ['index', 'show']]);
    }

    /**
     * Hakedişleri listele
     */
    public function index(Request $request)
    {
        $query = ProgressPayment::query();

        // Filtreler
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('contract_id')) {
            $query->where('contract_id', $request->contract_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('payment_date', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $progressPayments = $query->with(['project', 'contract', 'user', 'approver'])
            ->orderBy('payment_date', 'desc')
            ->paginate(20);

        $projects = Project::pluck('name', 'id');
        $contracts = Contract::pluck('number', 'id');
        $statuses = [
            'pending' => 'Beklemede',
            'submitted' => 'Sunuldu',
            'approved' => 'Onaylandı',
            'rejected' => 'Reddedildi',
            'paid' => 'Ödendi',
            'cancelled' => 'İptal Edildi',
        ];

        return view('progress_payments.index', compact('progressPayments', 'projects', 'contracts', 'statuses'));
    }

    /**
     * Hakediş oluşturma formu
     */
    public function create()
    {
        $projects = Project::pluck('name', 'id');
        $contracts = Contract::pluck('number', 'id');

        return view('progress_payments.create', compact('projects', 'contracts'));
    }

    /**
     * Hakediş kaydet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:digital_contracts,id',
            'project_id' => 'required|exists:projects,id',
            'payment_number' => 'required|unique:progress_payments,payment_number',
            'payment_date' => 'required|date',
            'due_date' => 'required|date|after:payment_date',
            'amount' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'description' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.description' => 'required|string',
            'details.*.quantity' => 'required|numeric|min:1',
            'details.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $payment = ProgressPayment::create([
                'contract_id' => $validated['contract_id'],
                'project_id' => $validated['project_id'],
                'user_id' => auth()->id(),
                'payment_number' => $validated['payment_number'],
                'payment_date' => $validated['payment_date'],
                'due_date' => $validated['due_date'],
                'amount' => $validated['amount'],
                'percentage' => $validated['percentage'],
                'description' => $validated['description'],
                'notes' => $validated['notes'] ?? null,
                'status' => ProgressPayment::STATUS_PENDING,
            ]);

            // Detayları kaydet
            foreach ($validated['details'] as $detail) {
                $amount = $detail['quantity'] * $detail['unit_price'];
                ProgressPaymentDetail::create([
                    'progress_payment_id' => $payment->id,
                    'description' => $detail['description'],
                    'quantity' => $detail['quantity'],
                    'unit_price' => $detail['unit_price'],
                    'amount' => $amount,
                ]);
            }

            DB::commit();

            return redirect()->route('progress-payments.show', $payment)
                ->with('success', 'Hakediş başarıyla kaydedildi');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Hakediş detaylarını göster
     */
    public function show(ProgressPayment $progressPayment)
    {
        $progressPayment->load(['project', 'contract', 'user', 'approver', 'details']);

        return view('progress_payments.show', compact('progressPayment'));
    }

    /**
     * Hakediş düzenleme formu
     */
    public function edit(ProgressPayment $progressPayment)
    {
        if ($progressPayment->status !== ProgressPayment::STATUS_PENDING) {
            return back()->with('error', 'Yalnızca beklemede olan hakedişler düzenlenebilir');
        }

        $projects = Project::pluck('name', 'id');
        $contracts = Contract::pluck('number', 'id');
        $progressPayment->load('details');

        return view('progress_payments.edit', compact('progressPayment', 'projects', 'contracts'));
    }

    /**
     * Hakediş güncelle
     */
    public function update(Request $request, ProgressPayment $progressPayment)
    {
        if ($progressPayment->status !== ProgressPayment::STATUS_PENDING) {
            return back()->with('error', 'Yalnızca beklemede olan hakedişler düzenlenebilir');
        }

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'due_date' => 'required|date|after:payment_date',
            'amount' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'description' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $progressPayment->update($validated);

        return redirect()->route('progress-payments.show', $progressPayment)
            ->with('success', 'Hakediş başarıyla güncellendi');
    }

    /**
     * Hakediş sil
     */
    public function destroy(ProgressPayment $progressPayment)
    {
        if (!in_array($progressPayment->status, [ProgressPayment::STATUS_PENDING, ProgressPayment::STATUS_REJECTED])) {
            return back()->with('error', 'Bu hakediş silinemez');
        }

        $progressPayment->delete();

        return redirect()->route('progress-payments.index')
            ->with('success', 'Hakediş silindi');
    }

    /**
     * Hakediş onay sayfası
     */
    public function approve(ProgressPayment $progressPayment)
    {
        if ($progressPayment->status !== ProgressPayment::STATUS_SUBMITTED) {
            return back()->with('error', 'Bu hakediş onaylanamaz');
        }

        $progressPayment->update([
            'status' => ProgressPayment::STATUS_APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('progress-payments.show', $progressPayment)
            ->with('success', 'Hakediş onaylandı');
    }

    /**
     * Hakediş reddetme
     */
    public function reject(Request $request, ProgressPayment $progressPayment)
    {
        $request->validate([
            'reject_reason' => 'required|string',
        ]);

        $progressPayment->update([
            'status' => ProgressPayment::STATUS_REJECTED,
            'notes' => ($progressPayment->notes ?? '') . "\n[Reddedilme Nedeni]: " . $request->reject_reason,
        ]);

        return redirect()->route('progress-payments.show', $progressPayment)
            ->with('success', 'Hakediş reddedildi');
    }

    /**
     * Hakediş gönder (Onaya hazırla)
     */
    public function submit(ProgressPayment $progressPayment)
    {
        if ($progressPayment->status !== ProgressPayment::STATUS_PENDING) {
            return back()->with('error', 'Bu hakediş sunulamaz');
        }

        $progressPayment->update([
            'status' => ProgressPayment::STATUS_SUBMITTED,
        ]);

        return redirect()->route('progress-payments.show', $progressPayment)
            ->with('success', 'Hakediş onay için gönderildi');
    }

    /**
     * Hakediş ödeme işaretle
     */
    public function markAsPaid(ProgressPayment $progressPayment)
    {
        if ($progressPayment->status !== ProgressPayment::STATUS_APPROVED) {
            return back()->with('error', 'Yalnızca onaylanmış hakedişler ödenebilir');
        }

        $progressPayment->update([
            'status' => ProgressPayment::STATUS_PAID,
            'paid_at' => now(),
        ]);

        return redirect()->route('progress-payments.show', $progressPayment)
            ->with('success', 'Hakediş ödenmiş olarak işaretlendi');
    }

    /**
     * Hakediş raporları
     */
    public function reports(Request $request)
    {
        $query = ProgressPayment::query();

        // Tarihe göre filtre
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('payment_date', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        // Proje bazında
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Durum bazında
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = [
            'total_amount' => $query->sum('amount'),
            'total_approved' => $query->where('status', ProgressPayment::STATUS_APPROVED)->sum('amount'),
            'total_paid' => $query->where('status', ProgressPayment::STATUS_PAID)->sum('amount'),
            'pending_payments' => $query->whereIn('status', [
                ProgressPayment::STATUS_PENDING,
                ProgressPayment::STATUS_SUBMITTED
            ])->sum('amount'),
            'overdue_count' => $query->overdue()->count(),
        ];

        $payments = $query->with(['project', 'contract'])
            ->orderBy('payment_date', 'desc')
            ->get();

        $projects = Project::pluck('name', 'id');

        return view('progress_payments.reports', compact('data', 'payments', 'projects'));
    }
}
