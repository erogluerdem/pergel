<?php

namespace App\Http\Controllers;

use App\Models\SalesTarget;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;

class SalesPerformanceController extends Controller
{
    /**
     * Satış performans dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'current_month'); // current_month, current_year, custom
        $userId = $request->get('user_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Tarih aralığını belirle
        [$start, $end] = $this->getDateRange($period, $startDate, $endDate);

        // Satış hedeflerini getir (tarih filtresi olmadan)
        $targets = SalesTarget::with('user')
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->get();

        // Satış verilerini hesapla
        $performance = $this->calculatePerformance($targets, $start, $end);

        // Kullanıcı listesi (yönetici ise tüm kullanıcılar, değilse sadece kendi)
        $consultants = auth()->user()->hasRole('admin')
            ? User::orderBy('name')->get()
            : User::where('id', auth()->id())->get();

        // İstatistikler
        $stats = [
            'total_target' => $performance->sum('target_amount'),
            'total_achieved' => $performance->sum('achieved_amount'),
            'total_count_target' => $performance->sum('target_count'),
            'total_count_achieved' => $performance->sum('achieved_count'),
        ];

        $stats['achievement_percentage'] = $stats['total_target'] > 0
            ? round(($stats['total_achieved'] / $stats['total_target']) * 100, 2)
            : 0;

        $stats['count_achievement'] = $stats['total_count_target'] > 0
            ? round(($stats['total_count_achieved'] / $stats['total_count_target']) * 100, 2)
            : 0;

        return view('sales_performance.index', compact('performance', 'stats', 'consultants', 'period', 'startDate', 'endDate'));
    }

    /**
     * Tarih aralığını belirle
     */
    private function getDateRange($period, $startDate, $endDate)
    {
        return match ($period) {
            'current_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'current_year' => [now()->startOfYear(), now()->endOfYear()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'last_3_months' => [now()->subMonths(3)->startOfMonth(), now()->endOfMonth()],
            'last_year' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            'custom' => [
                \Carbon\Carbon::createFromFormat('Y-m-d', $startDate),
                \Carbon\Carbon::createFromFormat('Y-m-d', $endDate),
            ],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    /**
     * Satış performansını hesapla
     */
    private function calculatePerformance($targets, $start, $end)
    {
        $hasInvoiceUser = \Schema::hasColumn('invoices', 'user_id');
        $hasInvoiceDate = \Schema::hasColumn('invoices', 'invoice_date') ? 'invoice_date' : 'date';

        return $targets->map(function ($target) use ($start, $end, $hasInvoiceUser, $hasInvoiceDate) {
            // Ödenen fatura / satış tutarını hesapla
            $paid = 0;
            $invoiceCount = 0;

            if ($hasInvoiceUser) {
                $paid = Invoice::where('user_id', $target->user_id)
                    ->whereDate($hasInvoiceDate, '>=', $start)
                    ->whereDate($hasInvoiceDate, '<=', $end)
                    ->sum('paid_amount') ?? 0;

                $invoiceCount = Invoice::where('user_id', $target->user_id)
                    ->whereDate($hasInvoiceDate, '>=', $start)
                    ->whereDate($hasInvoiceDate, '<=', $end)
                    ->count();
            } else {
                $paid = \App\Models\Opportunity::where('owner_id', $target->user_id)
                    ->where('stage', 'won')
                    ->whereBetween('updated_at', [$start, $end])
                    ->sum('estimated_value_kdv') ?? 0;

                $invoiceCount = \App\Models\Opportunity::where('owner_id', $target->user_id)
                    ->where('stage', 'won')
                    ->whereBetween('updated_at', [$start, $end])
                    ->count();
            }

            $targetAmount = $target->target_amount ?? ($target->amount ?? 0);

            return (object) [
                'user_id' => $target->user_id,
                'user_name' => $target->user->name ?? 'Bilinmiyor',
                'target_amount' => $targetAmount,
                'achieved_amount' => $paid,
                'achievement_percentage' => $targetAmount > 0
                    ? round(($paid / $targetAmount) * 100, 2)
                    : 0,
                'difference' => $paid - $targetAmount,
                'target_count' => $target->sales_count ?? 10,
                'achieved_count' => $invoiceCount,
                'count_percentage' => ($target->sales_count ?? 10) > 0
                    ? round(($invoiceCount / ($target->sales_count ?? 10)) * 100, 2)
                    : 0,
                'status' => match (true) {
                    ($paid >= $targetAmount && $targetAmount > 0) => 'achieved',
                    ($paid >= ($targetAmount * 0.8) && $targetAmount > 0) => 'good',
                    ($paid >= ($targetAmount * 0.5) && $targetAmount > 0) => 'warning',
                    default => 'danger',
                },
            ];
        });
    }

    /**
     * Danışman detay performansı
     */
    public function detail(User $user, Request $request)
    {
        $period = $request->get('period', 'current_month');
        [$start, $end] = $this->getDateRange($period, null, null);

        // Satış hedefi (tarih filtresi olmadan)
        $target = SalesTarget::where('user_id', $user->id)->first();
        $targetAmount = $target->target_amount ?? ($target->amount ?? 0);

        $hasInvoiceUser = \Schema::hasColumn('invoices', 'user_id');
        $hasInvoiceDate = \Schema::hasColumn('invoices', 'invoice_date') ? 'invoice_date' : 'date';

        $achieved = 0;
        $invoices = collect([]);

        if ($hasInvoiceUser) {
            $achieved = Invoice::where('user_id', $user->id)
                ->whereDate($hasInvoiceDate, '>=', $start)
                ->whereDate($hasInvoiceDate, '<=', $end)
                ->sum('paid_amount') ?? 0;

            $invoices = Invoice::where('user_id', $user->id)
                ->whereDate($hasInvoiceDate, '>=', $start)
                ->whereDate($hasInvoiceDate, '<=', $end)
                ->with('customer')
                ->latest($hasInvoiceDate)
                ->paginate(20);
        } else {
            $achieved = \App\Models\Opportunity::where('owner_id', $user->id)
                ->where('stage', 'won')
                ->whereBetween('updated_at', [$start, $end])
                ->sum('estimated_value_kdv') ?? 0;

            $invoices = Invoice::with('customer')->latest('id')->paginate(20);
        }

        // Başarı metriği
        $success = [
            'target_amount' => $targetAmount,
            'achieved_amount' => $achieved,
            'invoices' => $invoices,
        ];

        $success['achievement_percentage'] = $success['target_amount'] > 0
            ? round(($success['achieved_amount'] / $success['target_amount']) * 100, 2)
            : 0;

        return view('sales_performance.detail', compact('user', 'success', 'period'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('sales_performance.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'target_amount' => 'required|numeric|min:0',
            'target_type' => 'required|in:revenue,unit',
            'period_start' => 'required|date',
            'period_end' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);

        SalesTarget::create($validated);

        return redirect()->route('sales_performance.index')->with('success', 'Satış hedefi başarıyla eklendi.');
    }

    public function edit($id)
    {
        $target = SalesTarget::findOrFail($id);
        $users = User::orderBy('name')->get();
        return view('sales_performance.edit', compact('target', 'users'));
    }

    public function update(Request $request, $id)
    {
        $target = SalesTarget::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'target_amount' => 'required|numeric|min:0',
            'target_type' => 'required|in:revenue,unit',
            'period_start' => 'required|date',
            'period_end' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $target->update($validated);

        return redirect()->route('sales_performance.index')->with('success', 'Satış hedefi başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $target = SalesTarget::findOrFail($id);
        $target->delete();

        return redirect()->route('sales_performance.index')->with('success', 'Satış hedefi silindi.');
    }
}
