@extends('layouts.master')

@section('title', 'Mevsimsel Trend Analizi')

@section('topbar-title', 'Gelişmiş Raporlar - Mevsimsel Analiz')

@push('styles')
<style>
    .adv-rep-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0284c7 50%, #0369a1 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .adv-rep-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .adv-rep-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="adv-rep-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('advanced_reports.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Raporlar Hub
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Mevsimsel & Aylık Trend Analizi</h1>
            <p class="text-white-50 mb-0 fs-6">Aylık periyotlardaki satış hacimleri ve tahsilat başarı oranları.</p>
        </div>
    </div>
</div>

<div class="adv-rep-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-calendar-line text-success me-2"></i>12 Aylık Mevsimsel Döküm
        </h5>
        <span class="badge bg-success bg-opacity-10 border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold text-success">
            {{ $year }} Yılı
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Ay</th>
                    <th>Toplam Satış (₺)</th>
                    <th>Tahsil Edilen (₺)</th>
                    <th>Bekleyen Bakiye (₺)</th>
                    <th>Fatura Adedi</th>
                    <th>Tahsilat %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($analysis as $item)
                    <tr>
                        <td class="ps-4 font-monospace fw-bold text-dark">{{ $item['month'] }}. Ay</td>
                        <td>₺{{ number_format($item['total_amount'], 2, ',', '.') }}</td>
                        <td><strong class="text-success">₺{{ number_format($item['paid_amount'], 2, ',', '.') }}</strong></td>
                        <td><strong class="text-primary">₺{{ number_format($item['pending_amount'], 2, ',', '.') }}</strong></td>
                        <td>{{ $item['invoice_count'] }} Adet</td>
                        <td><span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1">%{{ number_format($item['collection_rate'], 1) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
