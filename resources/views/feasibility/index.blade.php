@extends('layouts.master')

@section('title', 'Fizibilite & Sermaye Planlama Portalı')
@section('topbar-title', 'Portföy Yönetimi - Fizibilite & Sermaye')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & AMBER GOLD GLASSMORPHIC THEME */
    .feas-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .feas-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #10b981, #3b82f6, #f59e0b);
        background-size: 300% 100%;
        animation: feasGlow 4s linear infinite;
    }

    @keyframes feasGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .feas-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Spec Cards */
    .custom-kpi-card-feas {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-kpi-card-feas:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .feas-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .custom-input-feas {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }

    .custom-input-feas:focus {
        background-color: #ffffff !important;
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15) !important;
    }

    .feas-table-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 20px !important;
        background: #ffffff !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
    }

    .feas-table thead th {
        background: #0f172a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 20px !important;
        border: none !important;
    }

    .feas-table tbody td {
        padding: 16px 20px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .feas-table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .ai-badge-success { background: #f0fdf4 !important; color: #16a34a !important; border: 1px solid #bbf7d0 !important; font-weight: 800 !important; }
    .ai-badge-warning { background: #fffbeb !important; color: #d97706 !important; border: 1px solid #fde68a !important; font-weight: 800 !important; }
    .ai-badge-danger { background: #fff1f2 !important; color: #e11d48 !important; border: 1px solid #fecdd3 !important; font-weight: 800 !important; }

    /* Modal Safety Fix */
    .modal {
        z-index: 1055 !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="feas-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill feas-badge-pill">
                        <i class="ri-calculator-line text-warning fs-5"></i> GAYRİMENKUL FİZİBİLİTE & SERMAYE MODELLEME PORTALI
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        NPV & IRR FİNANSAL SİMÜLATÖRÜ
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Fizibilite & Sermaye Planlama</h2>
                <p class="mb-0 fs-6" style="max-width: 820px; line-height: 1.6; color: #fef3c7; font-weight: 500;">
                    Gayrimenkul ve inşaat projelerinizin arsa maliyetlerini, m² birim satış gelirlerini, inşaat bütçelerini ve ROI karlılık oranlarını hesaplayın.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('feasibility.compare.get') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-scales-3-line fs-5"></i> Senaryoları Kıyasla
                    </a>
                    <a href="{{ route('feasibility.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-lg d-inline-flex align-items-center gap-2">
                        <i class="ri-add-circle-fill fs-4"></i> Yeni Fizibilite Oluştur
                    </a>
                    <a href="{{ route('feasibility.trash') }}" class="btn btn-outline-danger text-white rounded-pill px-3 py-2.5 fw-bold d-inline-flex align-items-center gap-1.5">
                        <i class="ri-delete-bin-line fs-5"></i> Çöp Kutusu
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ session('success') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-danger text-white" role="alert">
            <i class="ri-error-warning-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ session('error') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- 4 SUMMARY KPI SPEC CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-feas h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">AKTİF SENARYOLAR</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_studies'] }} Senaryo</div>
                        <small class="text-warning fw-bold"><i class="ri-folder-open-line me-1"></i>Tam Hesaplamalı Portföy</small>
                    </div>
                    <div class="feas-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-folder-chart-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-feas h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">ORTALAMA ROI GETİRİSİ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">%{{ number_format($stats['avg_roi'], 1) }}</div>
                        <small class="text-success fw-bold"><i class="ri-line-chart-line me-1"></i>Proje Karlılık Oranı</small>
                    </div>
                    <div class="feas-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-line-chart-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-feas h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">TOPLAM ARSA BÜTÇESİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.5rem;">₺{{ number_format($stats['total_land_cost'], 0, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-landscape-line me-1"></i>Portföy Arsa Maliyeti</small>
                    </div>
                    <div class="feas-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-map-pin-4-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-feas h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-extrabold text-uppercase d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">ORTALAMA İMALAT SÜRESİ</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ number_format($stats['avg_months'], 1) }} Ay</div>
                        <small class="text-primary fw-bold"><i class="ri-calendar-todo-line me-1"></i>İnşaat Takvimi</small>
                    </div>
                    <div class="feas-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-history-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TWO-COLUMN LAYOUT: QUICK CALCULATOR & FEASIBILITY INDEX TABLE -->
    <div class="row g-4 mb-4">

        <!-- LEFT COLUMN: LIVE QUICK FEASIBILITY CALCULATOR WIDGET -->
        <div class="col-xl-5 col-lg-6">
            <div class="card feas-table-container h-100 p-4">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: linear-gradient(135deg, #0f172a 0%, #d97706 100%);">
                            <i class="ri-calculator-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-extrabold text-dark mb-0">Hızlı İnşaat & Karlılık Simülatörü</h5>
                            <small class="text-muted fw-semibold">Emsal, inşaat ve satış gelirini anlık hesaplayın</small>
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-bold">ANLIK HESAP</span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="quick_land_area" class="form-label fw-extrabold text-dark small mb-1">ARSA ALANI (m²) <span class="text-danger">*</span></label>
                        <input type="number" id="quick_land_area" class="form-control custom-input-feas font-monospace" value="2500" oninput="runQuickFeasCalc()" placeholder="Örn: 2500">
                    </div>

                    <div class="col-md-6">
                        <label for="quick_kaks" class="form-label fw-extrabold text-dark small mb-1">EMSAL (KAKS / KAT ALANI)</label>
                        <input type="number" step="0.1" id="quick_kaks" class="form-control custom-input-feas font-monospace" value="2.0" oninput="runQuickFeasCalc()" placeholder="Örn: 2.0">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="quick_land_cost" class="form-label fw-extrabold text-dark small mb-1">ARSA MALİYETİ (TL) <span class="text-danger">*</span></label>
                        <input type="number" step="1000" id="quick_land_cost" class="form-control custom-input-feas font-monospace text-danger fw-bold" value="15000000" oninput="runQuickFeasCalc()" placeholder="Örn: 15000000">
                    </div>

                    <div class="col-md-6">
                        <label for="quick_cost_per_m2" class="form-label fw-extrabold text-dark small mb-1">M² İNŞAAT MALİYETİ (TL/m²)</label>
                        <input type="number" step="100" id="quick_cost_per_m2" class="form-control custom-input-feas font-monospace text-primary fw-bold" value="22000" oninput="runQuickFeasCalc()" placeholder="Örn: 22000">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="quick_sale_price_m2" class="form-label fw-extrabold text-dark small mb-1">TAHMİNİ M² SATIŞ FİYATI (TL/m²) <span class="text-danger">*</span></label>
                    <input type="number" step="500" id="quick_sale_price_m2" class="form-control custom-input-feas font-monospace text-success fw-bold" value="55000" oninput="runQuickFeasCalc()" placeholder="Örn: 55000">
                </div>

                <!-- SIMULATION OUTPUT DISPLAY -->
                <div class="p-3.5 bg-light rounded-4 border" style="border-color: #cbd5e1 !important; background: #f8fafc !important;">
                    <h6 class="fw-extrabold text-dark mb-3 border-bottom pb-2 d-flex align-items-center justify-content-between">
                        <span><i class="ri-pie-chart-2-line text-warning me-1"></i> Anlık Senaryo Çıktıları</span>
                        <small class="text-muted font-monospace fw-normal" id="quick_area_display">5.000 m² İnşaat</small>
                    </h6>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-bold small">Toplam İnşaat Bütçesi:</span>
                        <span class="font-monospace fw-extrabold text-primary fs-6" id="res_quick_const_cost">₺110.000.000</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-bold small">Toplam Proje Maliyeti (Arsa+İnşaat):</span>
                        <span class="font-monospace fw-extrabold text-danger fs-6" id="res_quick_total_cost">₺125.000.000</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-bold small">Tahmini Toplam Satış Hasılatı:</span>
                        <span class="font-monospace fw-extrabold text-success fs-6" id="res_quick_revenue">₺275.000.000</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary fw-bold small">Tahmini Brüt Proje Karı:</span>
                        <span class="font-monospace fw-extrabold text-dark fs-6" id="res_quick_profit">₺150.000.000</span>
                    </div>

                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="fw-extrabold text-dark">Yatırım Karlılık Oranı (ROI):</span>
                        <span class="font-monospace fw-extrabold text-warning fs-4" id="res_quick_roi">%120,0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: FEASIBILITY INDEX TABLE -->
        <div class="col-xl-7 col-lg-6">
            <div class="feas-table-container h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" style="background: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-table-line text-warning fs-5"></i> Fizibilite Senaryoları & Yatırım Modeli İndeksi
                    </h5>
                    <div class="d-flex align-items-center gap-2" style="max-width: 300px; width: 100%;">
                        <input type="text" id="feasSearchInput" class="form-control rounded-pill custom-input-feas py-1.5" placeholder="Senaryo veya proje türü ara...">
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table feas-table align-middle mb-0" id="feasibilityTable">
                            <thead>
                                <tr>
                                    <th class="ps-4">SENARYO ADI</th>
                                    <th>PROJE TÜRÜ</th>
                                    <th>ALAN (m²)</th>
                                    <th>ARSA BÜTÇESİ</th>
                                    <th>SATIŞ (₺/m²)</th>
                                    <th>ROI (%)</th>
                                    <th class="pe-4 text-end">İŞLEMLER</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studies as $study)
                                    @php
                                        $sum = $summaries[$study->id] ?? [];
                                        $roi = floatval($sum['roi'] ?? 0);
                                    @endphp
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-extrabold text-dark fs-6">{{ $study->name }}</div>
                                            <small class="text-secondary fw-semibold">Eklenme: {{ optional($study->created_at)->format('d.m.Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark font-monospace text-warning px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                                {{ $study->project_type ?? 'Konut / Karma' }}
                                            </span>
                                        </td>
                                        <td class="font-monospace fw-extrabold text-dark" style="font-size: 1rem;">
                                            {{ number_format($study->total_area ?? 0, 0, ',', '.') }} m²
                                        </td>
                                        <td class="font-monospace fw-extrabold text-primary" style="font-size: 1rem;">
                                            ₺{{ number_format($study->land_cost ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="font-monospace fw-bold text-success" style="font-size: 1rem;">
                                            ₺{{ number_format($study->unit_sale_price ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($roi >= 40)
                                                <span class="badge ai-badge-success rounded-pill px-3 py-1.5 fs-6 font-monospace">
                                                    <i class="ri-arrow-up-circle-fill me-1"></i>%{{ number_format($roi, 1) }}
                                                </span>
                                            @elseif($roi >= 20)
                                                <span class="badge ai-badge-warning rounded-pill px-3 py-1.5 fs-6 font-monospace">
                                                    <i class="ri-arrow-right-circle-fill me-1"></i>%{{ number_format($roi, 1) }}
                                                </span>
                                            @else
                                                <span class="badge ai-badge-danger rounded-pill px-3 py-1.5 fs-6 font-monospace">
                                                    <i class="ri-arrow-down-circle-fill me-1"></i>%{{ number_format($roi, 1) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-inline-flex gap-1">
                                                <a href="{{ route('feasibility.show', $study->id) }}" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-extrabold shadow-sm" title="Detay İncele">
                                                    <i class="ri-eye-line me-1"></i>İncele
                                                </a>
                                                <a href="{{ route('feasibility.edit', $study->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <form method="POST" action="{{ route('feasibility.duplicate', $study->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-circle p-2" title="Kopyala">
                                                        <i class="ri-file-copy-line"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('feasibility.destroy', $study->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirm('Bu fizibilite senaryosunu silmek istediğinize emin misiniz?')" title="Sil">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="ri-calculator-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Fizibilite Senaryosu Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sağ üstteki "+ Yeni Fizibilite Oluştur" butonuna tıklayarak ilk yatırım senaryonuzu ekleyebilirsiniz.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- MEVZUAT VE SERMAYE YATIRIM REHBERİ CARD -->
    <div class="card feas-table-container p-4">
        <h5 class="fw-extrabold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="ri-book-open-fill text-warning me-1"></i> Gayrimenkul Yatırım & Sermaye Modelleme Rehberi
        </h5>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded-3 border bg-light h-100">
                    <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="ri-scales-fill text-primary fs-5"></i> KAKSK & Emsal Hesabı
                    </h6>
                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
                        Kat Alanı Kat Sayısı (KAKS), arsa alanı üzerine inşa edilecek net ve brüt satılabilir toplam alanı belirler. Emsal harici sığınak, otopark ve merdiven boşlukları ek inşaat alanı sağlar.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 rounded-3 border bg-light h-100">
                    <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="ri-line-chart-fill text-success fs-5"></i> NPV & IRR Analitiği
                    </h6>
                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
                        Net Bugünkü Değer (NPV), projenin tüm nakit giriş ve çıkışlarını yıllık %15 iskonto oranıyla günümüz değerine indirger. İç Karlılık Oranı (IRR) projenin özsermaye verim eşiğini gösterir.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 rounded-3 border bg-light h-100">
                    <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="ri-shield-flash-fill text-warning fs-5"></i> Duyarlılık & Risk Senaryoları
                    </h6>
                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
                        Birim satış fiyatlarındaki ±%10 ve ±%20 sapmalara karşı projenin başabaş noktası ve borç ödeme kapasitesi (DSCR) simüle edilir.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
// LIVE QUICK FEASIBILITY CALCULATOR ENGINE
function runQuickFeasCalc() {
    const landArea = parseFloat(document.getElementById('quick_land_area').value) || 0;
    const kaks = parseFloat(document.getElementById('quick_kaks').value) || 0;
    const landCost = parseFloat(document.getElementById('quick_land_cost').value) || 0;
    const constCostPerM2 = parseFloat(document.getElementById('quick_cost_per_m2').value) || 0;
    const salePricePerM2 = parseFloat(document.getElementById('quick_sale_price_m2').value) || 0;

    const totalConstArea = landArea * kaks;
    const totalConstCost = totalConstArea * constCostPerM2;
    const totalProjectCost = landCost + totalConstCost;
    const totalRevenue = totalConstArea * salePricePerM2;
    const grossProfit = totalRevenue - totalProjectCost;
    const roi = totalProjectCost > 0 ? (grossProfit / totalProjectCost) * 100 : 0;

    const fmt = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', maximumFractionDigits: 0 });
    const fmtArea = new Intl.NumberFormat('tr-TR');

    document.getElementById('quick_area_display').textContent = `${fmtArea.format(totalConstArea)} m² İnşaat`;
    document.getElementById('res_quick_const_cost').textContent = fmt.format(totalConstCost);
    document.getElementById('res_quick_total_cost').textContent = fmt.format(totalProjectCost);
    document.getElementById('res_quick_revenue').textContent = fmt.format(totalRevenue);
    document.getElementById('res_quick_profit').textContent = fmt.format(grossProfit);
    document.getElementById('res_quick_roi').textContent = `%${roi.toFixed(1)}`;
}

// REALTIME SEARCH FILTER FOR FEASIBILITY TABLE
document.addEventListener('DOMContentLoaded', function() {
    runQuickFeasCalc();

    const searchInput = document.getElementById('feasSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#feasibilityTable tbody tr');
            rows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
