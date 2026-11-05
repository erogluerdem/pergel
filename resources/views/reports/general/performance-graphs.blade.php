@extends('layouts.master')

@section('title', 'Performans Grafikleri & Trend Analizi')

@section('topbar-title', 'Genel Raporlar - Performans Grafikleri')

@push('styles')
<style>
    .gen-kpi-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #7c3aed 50%, #6d28d9 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .gen-kpi-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .gen-kpi-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="gen-kpi-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('reports.general.kpi_dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>KPI Dashboard
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Performans Grafikleri & Trendler</h1>
            <p class="text-white-50 mb-0 fs-6">Aylık ciro trendi ve en yüksek ciroya sahip ilk 10 müşteri sıralaması.</p>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="gen-kpi-glass-card">
            <h5 class="fw-bold text-dark mb-3">
                <i class="ri-bar-chart-fill text-purple me-2" style="color:#7c3aed;"></i>Aylık Satış Trend Tablosu
            </h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ay / Yıl</th>
                            <th>Toplam Satış (₺)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesTrend as $st)
                            <tr>
                                <td class="font-monospace fw-bold text-dark">{{ $st['month'] }}</td>
                                <td><strong class="text-purple" style="color:#7c3aed;">₺{{ number_format($st['value'], 2, ',', '.') }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted">Kayıt bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="gen-kpi-glass-card">
            <h5 class="fw-bold text-dark mb-3">
                <i class="ri-trophy-line text-warning me-2"></i>En Yüksek Cirolu İlk 10 Müşteri
            </h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Sıra</th>
                            <th>Müşteri Adı</th>
                            <th>Toplam Hacim (₺)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topCustomers as $index => $tc)
                            <tr>
                                <td class="ps-3"><span class="badge bg-purple bg-opacity-10 text-purple rounded-circle p-2 fw-bold" style="color:#7c3aed;">#{{ $index + 1 }}</span></td>
                                <td><strong class="text-dark">{{ $tc['name'] }}</strong></td>
                                <td><strong class="text-success">₺{{ number_format($tc['total'], 2, ',', '.') }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Kayıt bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
