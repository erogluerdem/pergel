@extends('layouts.master')

@section('title', 'Satış Performansı Analizi & Hedef Takibi')

@push('styles')
<style>
    .sales-perf-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .sales-perf-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: salesPerfGlow 4s linear infinite;
    }

    @keyframes salesPerfGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .sales-perf-badge {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-perf {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-perf:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-card-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
    }

    .custom-input-perf {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }

    .custom-input-perf:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .custom-perf-table thead,
    .custom-perf-table thead tr,
    .custom-perf-table thead th {
        background-color: #0f172a !important;
        color: #ffffff !important;
        padding: 16px !important;
        font-weight: 800 !important;
        font-size: 0.85rem !important;
        border-bottom: 2px solid #d97706 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        --bs-table-bg: #0f172a !important;
        --bs-table-color: #ffffff !important;
    }

    .custom-perf-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 600 !important;
    }

    .owner-avatar-pill {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #0f172a;
        color: #fef08a;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        border: 2px solid #d97706;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="sales-perf-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill sales-perf-badge">
                        <i class="ri-trophy-fill text-warning fs-5"></i> SATIŞ PERFORMANCE & PRİM PORTALI
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ count($performance) }} Aktif Temsilci
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Satış Performansı Analizi & Hedef Takipleri</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Danışman bazlı ciro hedeflerini, gerçekleşen satışları, başarı oranlarını ve performans sıralamalarını izleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('sales_performance.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="ri-add-circle-fill fs-5"></i>+ Yeni Hedef Belirle
                </a>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-perf h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM CİRO HEDEFİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_target'] ?? 0, 0, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-flag-line me-1"></i>Ekip Bütçe Hedefi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-money-dollar-circle-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-perf h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">GERÇEKLEŞEN CİRO</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_achieved'] ?? 0, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-trophy-line me-1"></i>Tamamlanan Satış Hasılatı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-perf h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">GENEL BAŞARI ORANI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.6rem;">%{{ number_format($stats['achievement_percentage'] ?? 0, 1) }}</div>
                        <small class="text-primary fw-bold"><i class="ri-pie-chart-2-fill me-1"></i>Ortalama Tamamlanma</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-percent-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-perf h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">SATILAN BİRİM ADEDİ</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.6rem;">{{ number_format($stats['total_count_achieved'] ?? 0, 0, ',', '.') }} Satış</div>
                        <small class="text-info fw-bold"><i class="ri-shopping-cart-2-line me-1"></i>Kapanan Bağımsız Bölüm</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-building-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER BAR CARD -->
    <div class="card custom-card-container mb-4">
        <div class="card-body p-3.5">
            <form method="GET" action="{{ route('sales_performance.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.85rem;">Dönem Filtresi</label>
                    <select name="period" class="form-select custom-input-perf py-2">
                        <option value="current_month" {{ request('period', 'current_month') == 'current_month' ? 'selected' : '' }}>📅 Bu Ay</option>
                        <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>📅 Geçen Ay</option>
                        <option value="current_year" {{ request('period') == 'current_year' ? 'selected' : '' }}>📅 Bu Yıl</option>
                        <option value="last_3_months" {{ request('period') == 'last_3_months' ? 'selected' : '' }}>📅 Son 3 Ay</option>
                        <option value="last_year" {{ request('period') == 'last_year' ? 'selected' : '' }}>📅 Geçen Yıl</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-bold text-dark mb-1" style="font-size: 0.85rem;">Danışman / Temsilci</label>
                    <select name="user_id" class="form-select custom-input-perf py-2">
                        <option value="">-- Tüm Satış Temsilcileri --</option>
                        @foreach($consultants as $consultant)
                            <option value="{{ $consultant->id }}" {{ request('user_id') == $consultant->id ? 'selected' : '' }}>
                                👤 {{ $consultant->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-warning text-dark w-100 rounded-pill py-2 fw-extrabold shadow-sm">
                        <i class="ri-filter-3-line me-1"></i>Filtrele
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- LEADERBOARD TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-bar-chart-box-line text-warning fs-5"></i>Danışman Satış Performans Sıralaması (Leaderboard)
            </h5>
            <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                {{ count($performance) }} Temsilci
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-perf-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Satış Temsilcisi</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Hedef Ciro (₺)</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Gerçekleşen Ciro (₺)</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;" style="width: 220px;">Başarı İlerlemesi</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Fark (₺)</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Durum</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($performance as $p)
                            @php
                                $pct = min(100, max(0, round($p->achievement_percentage ?? 0, 1)));
                                $barColor = $pct >= 100 ? 'bg-success' : ($pct >= 75 ? 'bg-primary' : ($pct >= 50 ? 'bg-warning' : 'bg-danger'));
                                $userInitials = mb_substr($p->user_name ?? 'K', 0, 2);
                            @endphp
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="owner-avatar-pill flex-shrink-0">
                                            {{ strtoupper($userInitials) }}
                                        </div>
                                        <div>
                                            <strong class="text-dark d-block fs-6">{{ $p->user_name }}</strong>
                                            <small class="text-muted font-monospace">Temsilci ID: #{{ $p->user_id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end font-monospace fw-extrabold text-dark fs-6">
                                    ₺{{ number_format($p->target_amount ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-end font-monospace fw-extrabold text-success fs-6">
                                    ₺{{ number_format($p->achieved_amount ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-center font-monospace">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 10px; background: #e2e8f0; border-radius: 5px;">
                                            <div class="progress-bar {{ $barColor }}" style="width: {{ $pct }}%;"></div>
                                        </div>
                                        <span class="fw-extrabold text-dark" style="font-size: 12px;">%{{ $pct }}</span>
                                    </div>
                                </td>
                                <td class="text-end font-monospace fw-extrabold">
                                    @if(($p->difference ?? 0) >= 0)
                                        <span class="text-success">+₺{{ number_format($p->difference, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-danger">-₺{{ number_format(abs($p->difference), 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($p->status === 'achieved')
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">🏆 Hedef Aşıldı</span>
                                    @elseif($p->status === 'good')
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">⭐ İyi Seviyede</span>
                                    @elseif($p->status === 'warning')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">⚠️ Kritik Sınırda</span>
                                    @else
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #ef4444 !important;">🔻 Hedef Altında</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('sales_performance.detail', $p->user_id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="Performans Detayı">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="ri-award-line display-4 text-warning mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Henüz Performans Kaydı Bulunmuyor</h6>
                                    <p class="small text-muted mb-0">Üstteki "+ Yeni Hedef Belirle" butonuna tıklayarak temsilcileriniz için ciro hedefleri tanımlayabilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
