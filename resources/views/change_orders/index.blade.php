@extends('layouts.master')

@section('title', 'Ek Talepler & Değişiklik Emirleri')
@section('topbar-title', 'Sözleşme & Değişiklikler - Ek Talepler')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & AMBER GOLD GLASSMORPHIC THEME */
    .change-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .change-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #10b981, #3b82f6, #f59e0b);
        background-size: 300% 100%;
        animation: changeGlow 4s linear infinite;
    }

    @keyframes changeGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .change-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .change-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .change-table-card {
        border-radius: 20px !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff !important;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .change-table thead th {
        background: #0f172a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 20px !important;
        border: none !important;
    }

    .change-table tbody td {
        padding: 16px 20px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .change-table tbody tr:hover {
        background-color: #f8fafc !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="change-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        CHANGE ORDERS & SÖZLEŞME REVİZYONLARI
                    </span>
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 fw-bold">
                        BÜTÇE & MALİYET ETKİSİ
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Ek Talepler & Değişiklik Emirleri</h2>
                <p class="mb-0 fs-6" style="color: #fef3c7; font-weight: 500; max-width: 820px;">
                    Saha imalat revizyonlarını, ek metraj ve maliyet taleplerini, onay süreçlerini ve bütçe sapmalarını yönetin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('change_orders.create') }}" class="btn btn-warning text-dark rounded-pill py-3 px-4 fw-extrabold shadow-lg d-inline-flex align-items-center gap-2">
                    <i class="ri-add-circle-fill fs-4"></i> + Yeni Ek Talep Oluştur
                </a>
            </div>
        </div>
    </div>

    <!-- 4 SUMMARY KPI CARDS -->
    @php
        $totalCost = $orders->sum('cost');
        $approvedCost = $orders->where('status', 'Onaylandı')->sum('cost');
        $pendingCount = $orders->where('status', '!=', 'Onaylandı')->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card change-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">TOPLAM EK TALEP ADEDİ</small>
                <h2 class="fw-extrabold mb-0 text-dark font-monospace">{{ $orders->total() }} <small class="fs-6 text-muted">Talep</small></h2>
                <small class="text-warning fw-bold mt-2 d-block"><i class="ri-file-paper-line me-1"></i>Saha Revizyon Kayıtları</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card change-kpi-card h-100 border-start border-danger border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">TOPLAM TALEP MALİYETİ</small>
                <h2 class="fw-extrabold mb-0 text-danger font-monospace">₺{{ number_format($totalCost, 2, ',', '.') }}</h2>
                <small class="text-danger fw-bold mt-2 d-block"><i class="ri-money-dollar-circle-line me-1"></i>Brüt Ek Maliyet</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card change-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">ONAYLANAN MALİYET ETKİSİ</small>
                <h2 class="fw-extrabold mb-0 text-success font-monospace">₺{{ number_format($approvedCost, 2, ',', '.') }}</h2>
                <small class="text-success fw-bold mt-2 d-block"><i class="ri-checkbox-circle-fill me-1"></i>Bütçeye Eklenen Tutar</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card change-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">ONAY BEKLEYEN TALEPLER</small>
                <h2 class="fw-extrabold mb-0 text-info font-monospace">{{ $pendingCount }} <small class="fs-6 text-muted">Talep</small></h2>
                <small class="text-info fw-bold mt-2 d-block"><i class="ri-time-line me-1"></i>Değerlendirme Aşamasında</small>
            </div>
        </div>
    </div>

    <!-- TABLE CONTAINER -->
    <div class="change-table-card">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-git-pull-request-line text-warning fs-5"></i> Değişiklik Emirleri Listesi
            </h5>
            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-bold">{{ $orders->total() }} Kayıt</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table change-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">TALEP NO</th>
                            <th>KONU / REVİZYON ADI</th>
                            <th>DEĞİŞİKLİK NEDENİ</th>
                            <th>EK MALİYET (₺)</th>
                            <th>DURUM</th>
                            <th class="pe-4 text-end">ONAY İŞLEMİ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $o)
                            <tr>
                                <td class="ps-4 py-3">
                                    <strong class="font-monospace text-dark fs-6">#{{ $o->no ?? $o->id }}</strong>
                                </td>
                                <td>
                                    <strong class="text-dark fs-6 d-block mb-0.5">{{ $o->subject }}</strong>
                                </td>
                                <td>
                                    <span class="text-secondary fw-semibold small">{{ Str::limit($o->reason, 80) }}</span>
                                </td>
                                <td>
                                    <strong class="font-monospace text-danger fs-6">₺{{ number_format((float)$o->cost, 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($o->status === 'Onaylandı')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                            <i class="ri-checkbox-circle-fill me-1"></i>Onaylandı
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                            <i class="ri-time-line me-1"></i>{{ $o->status ?? 'In Review' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    @if($o->status !== 'Onaylandı')
                                        <form method="POST" action="{{ route('change_orders.approve', $o->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-emerald text-white rounded-pill px-4 fw-extrabold shadow-sm" style="background: #047857; border: none;">
                                                <i class="ri-check-line me-1"></i> Onayla
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-light text-success border border-success px-3 py-1.5 rounded-pill fw-bold font-monospace">
                                            <i class="ri-check-double-line me-1"></i>İşlem Tamamlandı
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="ri-git-pull-request-line display-4 text-secondary mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Henüz Ek Talep Bulunmuyor</h6>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 d-flex justify-content-between align-items-center border-top">
                <small class="text-muted font-monospace fw-bold">Toplam {{ $orders->total() }} talep listeleniyor</small>
                <div>{{ $orders->links() }}</div>
            </div>
        </div>
    </div>

</div>

@endsection
