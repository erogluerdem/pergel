@extends('layouts.master')

@section('title', 'Aylık Taksit Dağılımı')

@section('topbar-title', 'Satış Raporları - Taksit Dağılımı')

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
            <h1 class="fw-extrabold text-white mb-1">Aylık Taksit Tahsilat Dağılımı</h1>
            <p class="text-white-50 mb-0 fs-6">Aylık periyottaki taksit ödeme ve gerçekleşme oranları.</p>
        </div>
    </div>
</div>

<div class="sales-acc-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-calendar-check-line text-emerald me-2" style="color:#059669;"></i>Aylık Taksit Listesi
        </h5>
        <span class="badge bg-emerald bg-opacity-10 border border-emerald border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#059669;">
            {{ count($installments) }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Fatura No</th>
                    <th>Müşteri</th>
                    <th>Tarih</th>
                    <th>Toplam Tutar (₺)</th>
                    <th>Tahsil Edilen (₺)</th>
                    <th>Kalan Bakiye (₺)</th>
                    <th>Tahsilat %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($installments as $inst)
                    <tr>
                        <td class="ps-4"><span class="badge badge-code rounded-pill px-3 py-1 fw-bold fs-6">{{ $inst['invoice_number'] }}</span></td>
                        <td><strong class="text-dark">{{ $inst['customer_name'] }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($inst['invoice_date'])->format('d.m.Y') }}</td>
                        <td>₺{{ number_format($inst['total_amount'], 2, ',', '.') }}</td>
                        <td><strong class="text-success">₺{{ number_format($inst['paid_amount'], 2, ',', '.') }}</strong></td>
                        <td><strong class="text-emerald" style="color:#059669;">₺{{ number_format($inst['remaining_amount'], 2, ',', '.') }}</strong></td>
                        <td><span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1">%{{ number_format($inst['payment_percentage'], 1) }}</span></td>
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
