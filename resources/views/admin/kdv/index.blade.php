@extends('layouts.master')

@section('title', 'KDV Oranları & Mevzuat Yapılandırması')
@section('topbar-title', 'Yönetim - KDV Oranları')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & EMERALD GOLD GLASSMORPHIC THEME */
    .kdv-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #064e3b 50%, #047857 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .kdv-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #f59e0b, #3b82f6, #10b981);
        background-size: 300% 100%;
        animation: kdvGlow 4s linear infinite;
    }

    @keyframes kdvGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .kdv-badge-pill {
        background: rgba(16, 185, 129, 0.2) !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        color: #a7f3d0 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Cards */
    .kdv-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
    }

    .kdv-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .kdv-kpi-card.active-card { border-left: 5px solid #10b981 !important; }
    .kdv-kpi-card.standard-card { border-left: 5px solid #0284c7 !important; }
    .kdv-kpi-card.discounted-card { border-left: 5px solid #f59e0b !important; }
    .kdv-kpi-card.withholding-card { border-left: 5px solid #9333ea !important; }

    .kdv-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Form & Input Styling */
    .custom-input-kdv {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }

    .custom-input-kdv:focus {
        background-color: #ffffff !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
    }

    /* Calculator & Cards */
    .calc-card {
        border-radius: 20px !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff !important;
        overflow: hidden;
    }

    .kdv-table thead th {
        background: #0f172a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 20px !important;
        border: none !important;
    }

    .kdv-table tbody td {
        padding: 16px 20px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .kdv-table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    /* Modal Safety Fix */
    .modal {
        z-index: 1055 !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="kdv-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill kdv-badge-pill">
                        <i class="ri-scales-3-fill text-warning fs-5"></i> 3065 SAYILI KDV KANUNU REHBERİ
                    </span>
                    <span class="badge bg-emerald text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background-color: #047857;">
                        GİB E-FATURA & E-DEFTER UYUMLU
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">KDV Oranları & Mevzuat Yapılandırma Panosu</h2>
                <p class="mb-0 fs-6" style="max-width: 820px; line-height: 1.6; color: #d1fae5; font-weight: 500;">
                    Sistem genelinde teklif, hakediş, fatura ve maliyet modüllerinde kullanılan KDV oranlarını yönetin. Tevkifat tebliğleri, indirimli oran (%1, %10) ve standart oran (%20) parametrelerini yapılandırın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-column gap-2 float-lg-end">
                    <button type="button" class="btn btn-warning text-dark rounded-pill py-3 px-4 fw-extrabold shadow-lg d-inline-flex align-items-center justify-content-center gap-2" onclick="showNewKdvModal()">
                        <i class="ri-add-circle-fill fs-4"></i> Yeni KDV Oranı Tanımla
                    </button>
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

    <!-- 4 SUMMARY KPI CARDS -->
    @php
        $activeRatesCount = $rates->where('active', true)->count();
        $totalRatesCount = $rates->count();
        $standardRate = $rates->where('rate', 20)->first();
        $discountedRates = $rates->whereIn('rate', [1, 10]);
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="kdv-kpi-card active-card p-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">AKTİF KDV ORANLARI</span>
                        <h2 class="fw-extrabold mb-0 text-success">{{ $activeRatesCount }} <small class="fs-6 text-muted">/ {{ $totalRatesCount }} Oran</small></h2>
                    </div>
                    <div class="kdv-stat-icon bg-success-subtle text-success">
                        <i class="ri-checkbox-circle-fill fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-shield-check-line me-1"></i>Sistemde Etkin</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Aktif Kullanımda</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="kdv-kpi-card standard-card p-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">STANDART GENEL KDV ORANI</span>
                        <h2 class="fw-extrabold mb-0 text-primary">%20 <small class="fs-6 text-muted">Genel Oran</small></h2>
                    </div>
                    <div class="kdv-stat-icon bg-primary-subtle text-primary">
                        <i class="ri-percent-fill fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-file-list-3-line me-1"></i>Mal & Hizmet Alımları</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">GİB Standart</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="kdv-kpi-card discounted-card p-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">İNDİRİMLİ KDV ORANLARI</span>
                        <h2 class="fw-extrabold mb-0 text-warning">%1 & %10</h2>
                    </div>
                    <div class="kdv-stat-icon bg-warning-subtle text-warning">
                        <i class="ri-building-line fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-home-heart-line me-1"></i>Kentsel Dönüşüm / Konut</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">İadeye Tabi</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="kdv-kpi-card withholding-card p-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TEVKİFAT KAPSAMI</span>
                        <h2 class="fw-extrabold mb-0 text-purple" style="color: #9333ea;">7/10 & 5/10</h2>
                    </div>
                    <div class="kdv-stat-icon bg-purple-subtle text-purple" style="background: rgba(147, 51, 234, 0.12); color: #9333ea;">
                        <i class="ri-scissors-cut-fill fs-3"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-hammer-line me-1"></i>Şantiye Yapım İşleri</small>
                    <span class="badge bg-purple-subtle text-purple fw-bold" style="background: rgba(147, 51, 234, 0.1); color: #9333ea;">Stopajlı KDV</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TWO-COLUMN SECTION: SIMULATOR & KDV RATES TABLE -->
    <div class="row g-4 mb-4">

        <!-- LEFT COLUMN: LIVE KDV & TEVKİFAT CALCULATOR WIDGET -->
        <div class="col-xl-5 col-lg-6">
            <div class="card calc-card h-100 p-4">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: linear-gradient(135deg, #0f172a 0%, #047857 100%);">
                            <i class="ri-calculator-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-extrabold text-dark mb-0">Canlı KDV & Tevkifat Simülatörü</h5>
                            <small class="text-muted fw-semibold">Anlık matrah, KDV ve tevkifat hesabı yapın</small>
                        </div>
                    </div>
                    <span class="badge bg-emerald text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #047857;">ANLIK HESAP</span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="calc_amount" class="form-label fw-extrabold text-dark small mb-1">HESAPLANACAK TUTAR (TL) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text custom-input-kdv border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">₺</span>
                            <input type="number" step="0.01" id="calc_amount" class="form-control custom-input-kdv border-start-0 font-monospace text-primary fw-bold" value="100000" oninput="runKdvCalc()" style="border-radius: 0 10px 10px 0 !important;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="calc_mode" class="form-label fw-extrabold text-dark small mb-1">GİRİLEN TUTAR TÜRÜ</label>
                        <select id="calc_mode" class="form-select custom-input-kdv" onchange="runKdvCalc()">
                            <option value="excl" selected>KDV Hariç (Matrah)</option>
                            <option value="incl">KDV Dahil (Toplam Yekün)</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="calc_rate" class="form-label fw-extrabold text-dark small mb-1">UYGULANACAK KDV ORANI</label>
                        <select id="calc_rate" class="form-select custom-input-kdv" onchange="runKdvCalc()">
                            @foreach($rates as $r)
                                <option value="{{ $r->rate }}" {{ $r->rate == 20 ? 'selected' : '' }}>
                                    %{{ $r->rate }} — {{ $r->label ?: ('%'.$r->rate.' KDV Oranı') }}
                                </option>
                            @endforeach
                            <option value="1">1% - Kentsel Dönüşüm</option>
                            <option value="10">10% - İndirimli Konut</option>
                            <option value="20">20% - Standart Genel Oran</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="calc_withholding" class="form-label fw-extrabold text-dark small mb-1">KDV TEVKİFAT ORANI</label>
                        <select id="calc_withholding" class="form-select custom-input-kdv" onchange="runKdvCalc()">
                            <option value="0" selected>Tevkifatsız (%100 Beyan)</option>
                            <option value="0.7">7/10 (Yapım İşleri Tevkifatı)</option>
                            <option value="0.5">5/10 (İşçilik & Hizmet Temini)</option>
                            <option value="0.2">2/10 (Temizlik / Güvenlik)</option>
                            <option value="0.9">9/10 (Danışmanlık / Denetim)</option>
                        </select>
                    </div>
                </div>

                <!-- SIMULATION OUTPUT DISPLAY -->
                <div class="p-3.5 bg-light rounded-4 border" style="border-color: #cbd5e1 !important; background: #f8fafc !important;">
                    <h6 class="fw-extrabold text-dark mb-3 border-bottom pb-2 d-flex align-items-center justify-content-between">
                        <span><i class="ri-file-paper-2-line text-emerald me-1" style="color: #047857;"></i> Simülasyon Hesaplama Sonuçları</span>
                        <small class="text-muted font-monospace fw-normal" id="calc_rate_display">%20 KDV</small>
                    </h6>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-bold small">Net Matrah (KDV Hariç):</span>
                        <span class="font-monospace fw-extrabold text-dark fs-6" id="res_matrah">₺100.000,00</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-bold small">Hesaplanan Toplam KDV:</span>
                        <span class="font-monospace fw-extrabold text-primary fs-6" id="res_kdv">₺20.000,00</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-bold small">Alıcı Kesintisi (Tevkifat KDV):</span>
                        <span class="font-monospace fw-extrabold text-purple fs-6" style="color: #9333ea;" id="res_tevkifat">₺0,00</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary fw-bold small">Satıcı Beyan Edilecek Net KDV:</span>
                        <span class="font-monospace fw-extrabold text-info fs-6" id="res_beyan_kdv">₺20.000,00</span>
                    </div>

                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="fw-extrabold text-dark">Genel Toplam (Fatura Yekünü):</span>
                        <span class="font-monospace fw-extrabold text-success fs-5" id="res_total">₺120.000,00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: KDV RATES MANAGEMENT TABLE -->
        <div class="col-xl-7 col-lg-6">
            <div class="card calc-card h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-percent-line text-warning fs-5"></i> Sistem KDV Oranları Listesi
                    </h5>
                    <button type="button" class="btn btn-emerald btn-sm text-white rounded-pill px-3 fw-extrabold shadow-sm d-inline-flex align-items-center gap-1" onclick="showNewKdvModal()" style="background: #047857; border: none;">
                        <i class="ri-add-line"></i> Yeni Oran Ekle
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table kdv-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">ORAN (%)</th>
                                    <th>ETİKET / KULLANIM ALANI</th>
                                    <th>SIRA NO</th>
                                    <th>DURUM</th>
                                    <th class="pe-4 text-end">İŞLEMLER</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rates as $r)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge rounded-circle p-3 font-monospace fw-extrabold fs-6 d-flex align-items-center justify-content-center"
                                                      style="width: 48px; height: 48px; background: {{ $r->rate == 20 ? 'linear-gradient(135deg, #2563eb, #1d4ed8)' : ($r->rate == 10 ? 'linear-gradient(135deg, #d97706, #b45309)' : ($r->rate == 1 ? 'linear-gradient(135deg, #059669, #047857)' : 'linear-gradient(135deg, #475569, #334155)')) }}; color: #ffffff;">
                                                    %{{ $r->rate }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="text-dark d-block fs-6">{{ $r->label ?: ('%'.$r->rate.' KDV Oranı') }}</strong>
                                            <small class="text-secondary fw-semibold">
                                                @if($r->rate == 20)
                                                    <i class="ri-shield-check-line text-primary me-1"></i>Genel Standart KDV Oranı (3065 s.K.)
                                                @elseif($r->rate == 10)
                                                    <i class="ri-home-4-line text-warning me-1"></i>150m² Altı Konut Satışları
                                                @elseif($r->rate == 1)
                                                    <i class="ri-building-line text-success me-1"></i>Kentsel Dönüşüm / Dönüşümlü Konut
                                                @elseif($r->rate == 0)
                                                    <i class="ri-global-line text-info me-1"></i>Tam İstisna / İhracat
                                                @else
                                                    <i class="ri-price-tag-3-line me-1"></i>Özel Tanımlı KDV Oranı
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark font-monospace border px-3 py-1.5 rounded-pill fw-bold">
                                                Sıra: {{ $r->sort ?? $r->id }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($r->active)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold d-inline-flex align-items-center gap-1">
                                                    <i class="ri-checkbox-circle-fill"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold d-inline-flex align-items-center gap-1">
                                                    <i class="ri-close-circle-fill"></i> Pasif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="btn-group gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-warning rounded-circle d-inline-flex align-items-center justify-content-center"
                                                        style="width: 36px; height: 36px;"
                                                        onclick="editKdvModal({{ $r->id }}, {{ $r->rate }}, '{{ addslashes($r->label ?? '') }}', {{ $r->sort ?? 0 }}, {{ $r->active ? 1 : 0 }})"
                                                        title="Düzenle">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <form action="{{ route('admin.kdv.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu KDV oranını silmek istediğinize emin misiniz?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Sil">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="ri-percent-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark mb-1">Henüz KDV Oranı Tanımlanmadı</h6>
                                            <p class="small text-muted mb-0">Yukarıdaki buton ile yeni KDV oranı ekleyebilirsiniz.</p>
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

    <!-- MEVZUAT VE TEVKİFAT REHBERİ CARD -->
    <div class="card calc-card p-4">
        <h5 class="fw-extrabold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="ri-book-read-fill text-emerald me-1" style="color: #047857;"></i> Katma Değer Vergisi Mevzuat & Tevkifat Uygulama Rehberi
        </h5>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded-3 border bg-light h-100">
                    <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="ri-building-line text-success fs-5"></i> Kentsel Dönüşüm (%1 KDV)
                    </h6>
                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
                        6306 sayılı kanun kapsamında riskli yapı şerhi bulunan binaların kentsel dönüşüm teslimlerinde ve 150m² altı net konutlarda %1 KDV oranı uygulanır. Yüklenilen fazla KDV için dönemsel YMM iade raporu düzenlenir.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 rounded-3 border bg-light h-100">
                    <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="ri-hammer-line text-purple fs-5" style="color: #9333ea;"></i> Şantiye Yapım İşleri Tevkifatı (7/10)
                    </h6>
                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
                        Kamu kurumları ve belirlenmiş alıcılara karşı ifa edilen bina inşaat, bina yıkım ve altyapı taahhüt işlerinde hesaplanan KDV'nin 7/10'u işveren tarafından kesilerek doğrudan Vergi Dairesine (2 Nolu KDV) ödenir.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 rounded-3 border bg-light h-100">
                    <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="ri-hand-coin-line text-warning fs-5"></i> YMM İade Raporu Sınırları
                    </h6>
                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
                        İndirimli orana tabi işlemlerde nakden veya mahsuben KDV iadeleri GİB e-Vergi dairesi KDVİRA sistemi üzerinden veya YMM Tasdik Raporu ile incelemesiz olarak mükellef hesabına aktarılır.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- NEW KDV RATE MODAL -->
<div class="modal fade" id="newKdvModal" tabindex="-1" aria-labelledby="newKdvModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #064e3b 100%); border-bottom: 3px solid #10b981;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="newKdvModalLabel">
                    <i class="ri-percent-fill text-warning fs-4"></i> Yeni KDV Oranı Tanımla
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <form action="{{ route('admin.kdv.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4" style="background-color: #ffffff;">
                    <div class="mb-3">
                        <label for="modal_rate" class="form-label fw-extrabold text-dark">KDV ORANI (%) <span class="text-danger">*</span></label>
                        <input type="number" name="rate" id="modal_rate" class="form-control custom-input-kdv py-2.5 font-monospace fs-5 text-primary fw-bold" min="0" max="100" placeholder="Örn: 20" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal_label" class="form-label fw-extrabold text-dark">ETİKET / AÇIKLAMA</label>
                        <input type="text" name="label" id="modal_label" class="form-control custom-input-kdv py-2.5" placeholder="Örn: Kentsel Dönüşüm Konut Teslimi (%1)">
                    </div>

                    <div class="mb-3">
                        <label for="modal_sort" class="form-label fw-extrabold text-dark">SIRA NO (ÖNCELİK)</label>
                        <input type="number" name="sort" id="modal_sort" class="form-control custom-input-kdv py-2.5" value="1" min="0">
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="active" id="modal_active" value="1" checked style="cursor: pointer; width: 42px; height: 22px;">
                        <label class="form-check-label fw-extrabold text-dark ms-2" for="modal_active">Sistem Genelinde Aktif Olsun</label>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 fw-extrabold shadow-sm" style="background: #047857; border: none;">
                        <i class="ri-save-3-fill me-1"></i> KDV Oranını Sakla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT KDV RATE MODAL -->
<div class="modal fade" id="editKdvModal" tabindex="-1" aria-labelledby="editKdvModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-bottom: 3px solid #f59e0b;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="editKdvModalLabel">
                    <i class="ri-edit-fill text-warning fs-4"></i> KDV Oranını Güncelle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <form id="editKdvForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body p-4" style="background-color: #ffffff;">
                    <div class="mb-3">
                        <label for="edit_modal_rate" class="form-label fw-extrabold text-dark">KDV ORANI (%) <span class="text-danger">*</span></label>
                        <input type="number" name="rate" id="edit_modal_rate" class="form-control custom-input-kdv py-2.5 font-monospace fs-5 text-primary fw-bold" min="0" max="100" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_modal_label" class="form-label fw-extrabold text-dark">ETİKET / AÇIKLAMA</label>
                        <input type="text" name="label" id="edit_modal_label" class="form-control custom-input-kdv py-2.5">
                    </div>

                    <div class="mb-3">
                        <label for="edit_modal_sort" class="form-label fw-extrabold text-dark">SIRA NO (ÖNCELİK)</label>
                        <input type="number" name="sort" id="edit_modal_sort" class="form-control custom-input-kdv py-2.5" min="0">
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="active" id="edit_modal_active" value="1" style="cursor: pointer; width: 42px; height: 22px;">
                        <label class="form-check-label fw-extrabold text-dark ms-2" for="edit_modal_active">Sistem Genelinde Aktif Olsun</label>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-extrabold shadow-sm" style="background: #f59e0b; border: none;">
                        <i class="ri-save-3-fill me-1"></i> Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// SAFE MODAL LAUNCHERS PREVENTING OVERLAY BACKDROP TRAPS
function showNewKdvModal() {
    const modalEl = document.getElementById('newKdvModal');
    if (!modalEl) return;
    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
    if (window.bootstrap) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
}

function editKdvModal(id, rate, label, sort, active) {
    const modalEl = document.getElementById('editKdvModal');
    if (!modalEl) return;
    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
    const form = document.getElementById('editKdvForm');
    if (form) {
        form.action = `/admin/kdv/${id}`;
    }

    document.getElementById('edit_modal_rate').value = rate;
    document.getElementById('edit_modal_label').value = label;
    document.getElementById('edit_modal_sort').value = sort;
    document.getElementById('edit_modal_active').checked = (active == 1);

    if (window.bootstrap) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
}

// LIVE KDV & TEVKİFAT CALCULATOR ENGINE
function runKdvCalc() {
    const rawAmount = parseFloat(document.getElementById('calc_amount').value) || 0;
    const mode = document.getElementById('calc_mode').value; // excl or incl
    const kdvRateVal = parseFloat(document.getElementById('calc_rate').value) || 0;
    const withholdingVal = parseFloat(document.getElementById('calc_withholding').value) || 0;

    let matrah = 0;
    let kdvAmount = 0;

    if (mode === 'excl') {
        matrah = rawAmount;
        kdvAmount = matrah * (kdvRateVal / 100);
    } else {
        // Incl: Total = Matrah * (1 + rate/100)
        matrah = rawAmount / (1 + (kdvRateVal / 100));
        kdvAmount = rawAmount - matrah;
    }

    const withholdingAmount = kdvAmount * withholdingVal;
    const sellerDeclaredKdv = kdvAmount - withholdingAmount;
    const grandTotal = matrah + kdvAmount;

    // Formatter
    const fmt = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' });

    document.getElementById('calc_rate_display').textContent = `%${kdvRateVal} KDV Rate`;
    document.getElementById('res_matrah').textContent = fmt.format(matrah);
    document.getElementById('res_kdv').textContent = fmt.format(kdvAmount);
    document.getElementById('res_tevkifat').textContent = fmt.format(withholdingAmount);
    document.getElementById('res_beyan_kdv').textContent = fmt.format(sellerDeclaredKdv);
    document.getElementById('res_total').textContent = fmt.format(grandTotal);
}

// Run calculator on initial load
document.addEventListener('DOMContentLoaded', function() {
    runKdvCalc();
});
</script>
@endpush
