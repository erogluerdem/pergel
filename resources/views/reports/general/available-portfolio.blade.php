@extends('layouts.master')

@section('title', 'Müsait Portföy Analizi')

@section('topbar-title', 'Genel Raporlar - Müsait Portföy')

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
            <h1 class="fw-extrabold text-white mb-1">Müsait Gayrimenkul & Şantiye Portföyü</h1>
            <p class="text-white-50 mb-0 fs-6">Şantiye ve projelerdeki satılabilir stok değerleri ve müsaitlik oranları.</p>
        </div>
    </div>
</div>

<div class="gen-kpi-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-home-gear-line text-purple me-2" style="color:#7c3aed;"></i>Müsait Portföy Tablosu
        </h5>
        <span class="badge bg-purple bg-opacity-10 border border-purple border-opacity-30 rounded-pill px-3 py-1 fw-bold text-purple" style="color:#7c3aed;">
            {{ count($portfolio) }} Proje
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Proje / Şantiye Adı</th>
                    <th>Toplam Portföy Bütçesi (₺)</th>
                    <th>Satılan Tutar (₺)</th>
                    <th>Müsait Portföy Bakiyesi (₺)</th>
                    <th>Müsaitlik Oranı %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($portfolio as $p)
                    <tr>
                        <td class="ps-4"><strong class="text-dark fs-6">{{ $p['name'] }}</strong></td>
                        <td>₺{{ number_format($p['total_assets'], 2, ',', '.') }}</td>
                        <td><strong class="text-success">₺{{ number_format($p['sold_assets'], 2, ',', '.') }}</strong></td>
                        <td><strong class="text-purple" style="color:#7c3aed;">₺{{ number_format($p['available_assets'], 2, ',', '.') }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 8px;">
                                    <div class="progress-bar bg-purple" style="width: {{ min(100, $p['availability_percentage']) }}%; background:#7c3aed;"></div>
                                </div>
                                <span class="small fw-bold">%{{ number_format($p['availability_percentage'], 1) }}</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
