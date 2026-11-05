@extends('layouts.master')

@section('title', 'Bakiye Detay Analizi')

@section('topbar-title', 'Satış Raporları - Bakiye Analizi')

@push('styles')
<style>
    .sales-acc-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #059669 50%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .sales-acc-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .sales-acc-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="sales-acc-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('reports.sales.accounting_summary') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Muhasebe Özeti
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Satış Taksit Bakiye Detay Analizi</h1>
            <p class="text-white-50 mb-0 fs-6">Vadesi geçmiş ve gelecek taksit bakiyelerinin müşteri bazlı dökümü.</p>
        </div>
    </div>
</div>

<div class="sales-acc-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-scales-line text-emerald me-2" style="color:#059669;"></i>Müşteri Taksit Bakiye Tablosu
        </h5>
        <span class="badge bg-emerald bg-opacity-10 border border-emerald border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#059669;">
            {{ count($balances) }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Sözleşme No</th>
                    <th>Müşteri</th>
                    <th>Fatura Tutarı (₺)</th>
                    <th>Tahsil Edilen (₺)</th>
                    <th>Kalan Bakiye (₺)</th>
                    <th>Tahsilat Oranı</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                @forelse($balances as $b)
                    <tr>
                        <td class="ps-4"><span class="badge badge-code rounded-pill px-3 py-1 fw-bold fs-6">{{ $b['invoice_number'] }}</span></td>
                        <td><strong class="text-dark">{{ $b['customer_name'] }}</strong></td>
                        <td>₺{{ number_format($b['invoice_amount'], 2, ',', '.') }}</td>
                        <td><strong class="text-success">₺{{ number_format($b['paid_amount'], 2, ',', '.') }}</strong></td>
                        <td><strong class="text-emerald" style="color:#059669;">₺{{ number_format($b['remaining_amount'], 2, ',', '.') }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 8px;">
                                    <div class="progress-bar bg-emerald" style="width: {{ min(100, $b['payment_percentage']) }}%; background:#059669;"></div>
                                </div>
                                <span class="small fw-bold">%{{ number_format($b['payment_percentage'], 1) }}</span>
                            </div>
                        </td>
                        <td>
                            @if($b['is_overdue'])
                                <span class="badge bg-danger bg-opacity-10 text-danger border rounded-pill px-3 py-1 fw-bold">Gecikmiş Bakiye</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1 fw-bold">Normal</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
