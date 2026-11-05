@extends('layouts.master')

@section('title', 'Sözleşme Yönetimi & Taşeron Portföyü')
@section('topbar-title', 'Finans - Sözleşme Yönetimi')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & EMERALD GOLD GLASSMORPHIC THEME */
    .contract-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #065f46 50%, #047857 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .contract-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #f59e0b, #3b82f6, #10b981);
        background-size: 300% 100%;
        animation: contractGlow 4s linear infinite;
    }

    @keyframes contractGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .contract-badge-pill {
        background: rgba(16, 185, 129, 0.2) !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        color: #a7f3d0 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .contract-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .contract-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .contract-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Form & Input Styling */
    .custom-input-contract {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }

    .custom-input-contract:focus {
        background-color: #ffffff !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
    }

    /* Cards & Container */
    .contract-glass-card {
        border-radius: 20px !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff !important;
        overflow: hidden;
    }

    .contract-table thead th {
        background: #0f172a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 20px !important;
        border: none !important;
    }

    .contract-table tbody td {
        padding: 16px 20px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .contract-table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .progress-bar-custom {
        height: 8px;
        border-radius: 10px;
        background-color: #e2e8f0;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 10px;
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
    <div class="contract-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill contract-badge-pill">
                        <i class="ri-file-paper-2-line text-warning fs-5"></i> TAŞERON & TEDARİKÇİ PORTFÖYÜ
                    </span>
                    <span class="badge bg-emerald text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background-color: #047857;">
                        HUKUK & FİNANS HAKEDİŞ TAKİBİ
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Sözleşme Yönetimi & Taşeron Portföyü</h2>
                <p class="mb-0 fs-6" style="max-width: 820px; line-height: 1.6; color: #d1fae5; font-weight: 500;">
                    Proje sözleşmelerini, hakediş bakiyelerini, teminat mektuplarını, ceza/gecikme şartnamelerini ve hazır hukuki madde kütüphanesini yönetin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-column gap-2 float-lg-end">
                    <button type="button" class="btn btn-warning text-dark rounded-pill py-3 px-4 fw-extrabold shadow-lg d-inline-flex align-items-center justify-content-center gap-2" onclick="showNewContractModal()">
                        <i class="ri-add-circle-fill fs-4"></i> Yeni Sözleşme Oluştur
                    </button>
                    <a href="{{ url('/contracts/library') }}" target="_blank" class="btn btn-outline-light rounded-pill py-2 px-3 fw-bold d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="ri-book-read-line fs-5"></i> Şablon Kütüphanesini İncele
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

    <!-- 4 SUMMARY KPI CARDS -->
    @php
        $totalValue = $contracts->sum('value');
        $totalPaid = $contracts->sum('paid');
        $totalBalance = max(0, $totalValue - $totalPaid);
        $paidPercentage = $totalValue > 0 ? round(($totalPaid / $totalValue) * 100, 1) : 0;
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card contract-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">KAYITLI SÖZLEŞMELER</span>
                        <h2 class="fw-extrabold mb-0 text-primary">{{ $contracts->total() }} <small class="fs-6 text-muted">Sözleşme</small></h2>
                    </div>
                    <div class="contract-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-file-list-3-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-checkbox-circle-line me-1"></i>Portföyde Tanımlı</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Aktif İndeks</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card contract-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TOPLAM PORTFÖY BEDELİ</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace" style="font-size: 1.5rem;">₺{{ number_format($totalValue, 2, ',', '.') }}</h2>
                    </div>
                    <div class="contract-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-hand-coin-line me-1"></i>Taahhüt Toplamı</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Brüt Hakediş</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card contract-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">ÖDENEN HAKEDİŞ</span>
                        <h2 class="fw-extrabold mb-0 text-info font-monospace" style="font-size: 1.5rem;">₺{{ number_format($totalPaid, 2, ',', '.') }}</h2>
                    </div>
                    <div class="contract-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-check-double-line me-1"></i>Tamamlanan Ödeme</small>
                    <span class="badge bg-info-subtle text-info fw-bold">%{{ $paidPercentage }} Tamamlandı</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card contract-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">KALAN TAAHHÜT BAKİYE</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace" style="font-size: 1.5rem;">₺{{ number_format($totalBalance, 2, ',', '.') }}</h2>
                    </div>
                    <div class="contract-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-time-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-scales-3-line me-1"></i>Gelecek Hakedişler</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Ödenecek Kalan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="card contract-glass-card mb-4 p-4">
        <form method="GET" action="{{ route('contracts.management') }}">
            <div class="row g-3 align-items-center">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-extrabold text-dark small mb-1">Arama Kriteri</label>
                    <div class="input-group">
                        <span class="input-group-text custom-input-contract border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                            <i class="ri-search-line text-muted fs-5"></i>
                        </span>
                        <input type="text" name="q" id="contractSearchInput" class="form-control custom-input-contract border-start-0 py-2" placeholder="Sözleşme adı, no veya firma ara..." value="{{ request('q') }}" style="border-radius: 0 10px 10px 0 !important;">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-extrabold text-dark small mb-1">Proje Filtresi</label>
                    <select name="project" class="form-select custom-input-contract py-2">
                        <option value="">Tüm Projeler (Filtresiz)</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ request('project') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-extrabold text-dark small mb-1">Sözleşme Türü</label>
                    <select name="type" class="form-select custom-input-contract py-2">
                        <option value="">Tüm Sözleşme Tipleri</option>
                        <option value="Taşeron" {{ request('type') == 'Taşeron' ? 'selected' : '' }}>🔨 Taşeron Yüklenici</option>
                        <option value="Tedarik" {{ request('type') == 'Tedarik' ? 'selected' : '' }}>📦 Malzeme & Tedarik</option>
                        <option value="Hizmet" {{ request('type') == 'Hizmet' ? 'selected' : '' }}>📐 Hizmet & Müşavirlik</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6 d-flex gap-2 align-self-end">
                    <button type="submit" class="btn btn-emerald text-white rounded-pill flex-fill py-2 fw-extrabold shadow-sm" style="background: #047857; border: none;">
                        <i class="ri-filter-3-line me-1"></i> Filtrele
                    </button>
                    <a href="{{ route('contracts.management') }}" class="btn btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Sıfırla">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- MAIN TWO-COLUMN SECTION: CONTRACTS TABLE & CALCULATOR/SIDEBAR -->
    <div class="row g-4 mb-4">

        <!-- LEFT COLUMN: CONTRACTS TABLE -->
        <div class="col-xl-8 col-lg-7">
            <div class="card contract-glass-card h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-file-paper-2-line text-warning fs-5"></i> Kayıtlı Sözleşmeler Portföyü
                    </h5>
                    <span class="badge bg-emerald text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #047857;">{{ $contracts->total() }} Kayıt</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table contract-table align-middle mb-0" id="contractsTable">
                            <thead>
                                <tr>
                                    <th class="ps-4">SÖZLEŞME ADI / NO</th>
                                    <th>YÜKLENİCİ & PROJE</th>
                                    <th>TÜR</th>
                                    <th>BEDEL & HAKEDİŞ İLERLEMESİ</th>
                                    <th>DURUM</th>
                                    <th class="pe-4 text-end">İŞLEMLER</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contracts as $c)
                                    @php
                                        $cVal = (float) $c->value;
                                        $cPaid = (float) $c->paid;
                                        $cBal = max(0, $cVal - $cPaid);
                                        $cPercent = $cVal > 0 ? min(100, round(($cPaid / $cVal) * 100)) : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <strong class="text-dark d-block fs-6 mb-0.5">
                                                <a href="{{ route('contracts.show', $c->id) }}" class="text-dark text-decoration-none fw-extrabold hover-emerald">
                                                    {{ $c->name }}
                                                </a>
                                            </strong>
                                            <span class="badge bg-light text-dark font-monospace border px-2.5 py-1 rounded-pill fw-bold" style="font-size: 11px;">
                                                <i class="ri-barcode-line text-muted me-1"></i>{{ $c->number ?? 'SZ-NO-YOK' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-extrabold text-dark fs-6">{{ $c->company ?? 'Firma Belirtilmemiş' }}</div>
                                            <small class="text-secondary fw-semibold">
                                                <i class="ri-building-2-line me-1 text-primary"></i>{{ $c->project ? $c->project->name : 'Genel Proje' }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark font-monospace text-warning px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                                {{ $c->type ?? 'Genel' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <strong class="text-dark font-monospace" style="font-size: 1.05rem;">₺{{ number_format($cVal, 2, ',', '.') }}</strong>
                                                <small class="font-monospace fw-bold text-success">%{{ $cPercent }}</small>
                                            </div>
                                            <div class="progress progress-bar-custom mb-1">
                                                <div class="progress-bar-fill bg-success" style="width: {{ $cPercent }}%;"></div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center text-muted small" style="font-size: 11px;">
                                                <span>Ödenen: ₺{{ number_format($cPaid, 0, ',', '.') }}</span>
                                                <span class="text-warning font-monospace">Kalan: ₺{{ number_format($cBal, 0, ',', '.') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @php $st = strtolower($c->status ?? 'active'); @endphp
                                            @if($st === 'active' || $st === 'aktif')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>Aktif
                                                </span>
                                            @elseif($st === 'completed' || $st === 'tamamlandi')
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                                    <i class="ri-check-double-fill me-1"></i>Tamamlandı
                                                </span>
                                            @elseif($st === 'pending' || $st === 'onay_bekliyor')
                                                <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">
                                                    <i class="ri-time-line me-1"></i>Onay Bekliyor
                                                </span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">{{ $c->status }}</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="btn-group gap-1">
                                                <a href="{{ route('contracts.show', $c->id) }}" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-extrabold shadow-sm" title="İncele">
                                                    <i class="ri-eye-line me-1"></i>İncele
                                                </a>
                                                <a href="{{ route('contracts.edit', $c->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @if(\Illuminate\Support\Facades\Route::has('contracts.documents.index'))
                                                    <a href="{{ route('contracts.documents.index', $c->id) }}" class="btn btn-sm btn-outline-info rounded-circle p-2" title="Dokümanlar">
                                                        <i class="ri-folder-open-line"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="ri-file-paper-2-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Kayıtlı Sözleşme Bulunamadı</h6>
                                            <p class="small text-muted mb-0">Yukarıdaki buton veya filtre seçeneğiyle arama yapabilirsiniz.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 d-flex justify-content-between align-items-center border-top">
                        <small class="text-muted font-monospace fw-bold">Toplam {{ $contracts->total() }} sözleşme listeleniyor</small>
                        <div>{{ $contracts->links() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: LIVE CONTRACT & RETENTION CALCULATOR + TEMPLATE LIBRARY -->
        <div class="col-xl-4 col-lg-5">
            
            <!-- LIVE CONTRACT & RETENTION CALCULATOR WIDGET -->
            <div class="card contract-glass-card p-4 mb-4">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: linear-gradient(135deg, #0f172a 0%, #047857 100%);">
                            <i class="ri-calculator-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-extrabold text-dark mb-0">Hızlı Hakediş & Kesinti Simülatörü</h5>
                            <small class="text-muted fw-semibold">Stopaj, Nakit Teminat & Net Hakediş Hesabı</small>
                        </div>
                    </div>
                    <span class="badge bg-emerald text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #047857;">ANLIK HESAP</span>
                </div>

                <div class="mb-3">
                    <label for="calc_contract_val" class="form-label fw-extrabold text-dark small mb-1">SÖZLEŞME BEDELİ (TL) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text custom-input-contract border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">₺</span>
                        <input type="number" step="1000" id="calc_contract_val" class="form-control custom-input-contract border-start-0 font-monospace text-primary fw-bold" value="5000000" oninput="runContractCalc()" style="border-radius: 0 10px 10px 0 !important;">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="calc_progress_pct" class="form-label fw-extrabold text-dark small mb-1">HAKEDİŞ TAMAMLAMA (%)</label>
                        <input type="number" step="1" id="calc_progress_pct" class="form-control custom-input-contract font-monospace" value="25" oninput="runContractCalc()" placeholder="Örn: 25">
                    </div>

                    <div class="col-md-6">
                        <label for="calc_retention_pct" class="form-label fw-extrabold text-dark small mb-1">NAKİT TEMİNAT (% KESİNTİ)</label>
                        <input type="number" step="1" id="calc_retention_pct" class="form-control custom-input-contract font-monospace" value="5" oninput="runContractCalc()" placeholder="Örn: 5">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="calc_stoppage_pct" class="form-label fw-extrabold text-dark small mb-1">GELİR / STOPAJ VERGİSİ (% KESİNTİ)</label>
                    <select id="calc_stoppage_pct" class="form-select custom-input-contract" onchange="runContractCalc()">
                        <option value="5" selected>%5 — Yapım İşleri Stopajı (GVK Madde 94)</option>
                        <option value="3">%3 — Hizmet & Danışmanlık Stopajı</option>
                        <option value="0">%0 — Stopajsız (Tedarik Mal Alımı)</option>
                    </select>
                </div>

                <!-- CALCULATION OUTPUT -->
                <div class="p-3.5 bg-light rounded-4 border" style="border-color: #cbd5e1 !important; background: #f8fafc !important;">
                    <h6 class="fw-extrabold text-dark mb-3 border-bottom pb-2 d-flex align-items-center justify-content-between">
                        <span><i class="ri-file-list-3-line text-emerald me-1" style="color: #047857;"></i> Hakediş Hesabı Detayı</span>
                        <small class="text-muted font-monospace fw-normal" id="calc_pct_display">%25 Tamamlanan</small>
                    </h6>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-bold small">Brüt Hakediş Tutarı:</span>
                        <span class="font-monospace fw-extrabold text-dark fs-6" id="res_gross_hakedis">₺1.250.000,00</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary fw-bold small">Stopaj Vergisi Kesintisi:</span>
                        <span class="font-monospace fw-extrabold text-danger fs-6" id="res_stoppage_amt">₺62.500,00</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary fw-bold small">Nakit Teminat Kesintisi:</span>
                        <span class="font-monospace fw-extrabold text-purple fs-6" style="color: #9333ea;" id="res_retention_amt">₺62.500,00</span>
                    </div>

                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                        <span class="fw-extrabold text-dark">Taşerona Ödenecek Net Tutar:</span>
                        <span class="font-monospace fw-extrabold text-success fs-5" id="res_net_payment">₺1.125.000,00</span>
                    </div>
                </div>
            </div>

            <!-- TEMPLATES & CLAUSES SIDEBAR -->
            <div class="card contract-glass-card p-4">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center justify-content-between">
                    <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="ri-book-3-fill text-warning me-1"></i> Şablon & Madde Kütüphanesi
                    </h5>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-bold">{{ count($templates) + count($clauses) }} Öğe</span>
                </div>

                <!-- Nav Tabs -->
                <ul class="nav nav-pills nav-justified mb-3 gap-2" id="libraryTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill fw-bold py-1.5" id="templates-tab" data-bs-toggle="tab" data-bs-target="#templates-pane" type="button" role="tab">
                            <i class="ri-file-word-line me-1"></i> Şablonlar ({{ count($templates) }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-bold py-1.5" id="clauses-tab" data-bs-toggle="tab" data-bs-target="#clauses-pane" type="button" role="tab">
                            <i class="ri-scales-3-line me-1"></i> Maddeler ({{ count($clauses) }})
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="libraryTabContent">
                    <!-- Templates Pane -->
                    <div class="tab-pane fade show active" id="templates-pane" role="tabpanel">
                        <div class="list-group list-group-flush">
                            @forelse($templates as $t)
                                <div class="list-group-item bg-light rounded-3 p-3 mb-2 border" style="border-color: #cbd5e1 !important;">
                                    <strong class="text-dark d-block mb-1 fs-6">
                                        <i class="ri-file-text-fill text-emerald me-1" style="color: #047857;"></i>{{ is_object($t) ? $t->title : $t }}
                                    </strong>
                                    <small class="text-muted d-block text-truncate" style="max-width: 300px;">
                                        {{ Str::limit(is_object($t) ? $t->body : '', 80) }}
                                    </small>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="ri-file-warning-line fs-3 d-block mb-1"></i>
                                    Henüz kayıtlı sözleşme şablonu bulunmuyor.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Clauses Pane -->
                    <div class="tab-pane fade" id="clauses-pane" role="tabpanel">
                        <div class="list-group list-group-flush">
                            @forelse($clauses as $cl)
                                <div class="list-group-item bg-light rounded-3 p-3 mb-2 border" style="border-color: #cbd5e1 !important;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-dark fs-6">
                                            <i class="ri-checkbox-circle-fill text-success me-1"></i>{{ is_object($cl) ? $cl->title : $cl }}
                                        </strong>
                                        <span class="badge bg-dark text-warning rounded-pill px-2 py-0.5 small font-monospace">
                                            {{ is_object($cl) ? $cl->category : 'Hukuki' }}
                                        </span>
                                    </div>
                                    <small class="text-muted d-block text-truncate" style="max-width: 300px;">
                                        {{ is_object($cl) ? $cl->content : '' }}
                                    </small>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="ri-scales-3-line fs-3 d-block mb-1"></i>
                                    Madde kütüphanesi boş.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- NEW CONTRACT MODAL -->
<div class="modal fade" id="newContractModal" tabindex="-1" aria-labelledby="newContractModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #065f46 100%); border-bottom: 3px solid #10b981;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="newContractModalLabel">
                    <i class="ri-file-add-fill text-warning fs-4"></i> Yeni Sözleşme Kaydı Oluştur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <form action="{{ route('contracts.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4" style="background-color: #ffffff;">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label for="modal_contract_name" class="form-label fw-extrabold text-dark">SÖZLEŞME ADI / KONUSU <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="modal_contract_name" class="form-control custom-input-contract py-2.5" placeholder="Örn: A-Blok İnce Yapı Taşeron Sözleşmesi" required>
                        </div>
                        <div class="col-md-4">
                            <label for="modal_contract_num" class="form-label fw-extrabold text-dark">SÖZLEŞME NO</label>
                            <input type="text" name="number" id="modal_contract_num" class="form-control custom-input-contract py-2.5 font-monospace" placeholder="Örn: SZ-2026-004">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="modal_project_id" class="form-label fw-extrabold text-dark">İLGİLİ PROJE</label>
                            <select name="project_id" id="modal_project_id" class="form-select custom-input-contract py-2.5">
                                <option value="">Proje Seçiniz (Opsiyonel)</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_company" class="form-label fw-extrabold text-dark">YÜKLENİCİ / TAŞERON FİRMA <span class="text-danger">*</span></label>
                            <input type="text" name="company" id="modal_company" class="form-control custom-input-contract py-2.5" placeholder="Örn: Özkan İnşaat Ltd. Şti." required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="modal_type" class="form-label fw-extrabold text-dark">SÖZLEŞME TÜRÜ</label>
                            <select name="type" id="modal_type" class="form-select custom-input-contract py-2.5">
                                <option value="Taşeron" selected>🔨 Taşeron Yüklenici</option>
                                <option value="Tedarik">📦 Malzeme & Tedarik</option>
                                <option value="Hizmet">📐 Hizmet & Müşavirlik</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_value" class="form-label fw-extrabold text-dark">SÖZLEŞME TOPLAM BEDELİ (TL)</label>
                            <input type="number" step="0.01" name="value" id="modal_value" class="form-control custom-input-contract py-2.5 font-monospace text-primary fw-bold" placeholder="Örn: 2500000.00">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="modal_start_date" class="form-label fw-extrabold text-dark">BAŞLANGIÇ TARİHİ</label>
                            <input type="date" name="start_date" id="modal_start_date" class="form-control custom-input-contract py-2.5" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="modal_end_date" class="form-label fw-extrabold text-dark">BİTİŞ TARİHİ</label>
                            <input type="date" name="end_date" id="modal_end_date" class="form-control custom-input-contract py-2.5">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="modal_notes" class="form-label fw-extrabold text-dark">NOTLAR & HUKUKİ ŞARTLAR</label>
                        <textarea name="notes" id="modal_notes" class="form-control custom-input-contract" rows="3" placeholder="Sözleşme özel şartları, ceza maddeleri ve teminat detayları..."></textarea>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 fw-extrabold shadow-sm" style="background: #047857; border: none;">
                        <i class="ri-save-3-fill me-1"></i> Sözleşmeyi Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// SAFE MODAL LAUNCHER PREVENTING OVERLAY BACKDROP TRAPS
function showNewContractModal() {
    const modalEl = document.getElementById('newContractModal');
    if (!modalEl) return;
    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
    if (window.bootstrap) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
}

// LIVE CONTRACT & RETENTION CALCULATOR ENGINE
function runContractCalc() {
    const contractVal = parseFloat(document.getElementById('calc_contract_val').value) || 0;
    const progressPct = parseFloat(document.getElementById('calc_progress_pct').value) || 0;
    const retentionPct = parseFloat(document.getElementById('calc_retention_pct').value) || 0;
    const stoppagePct = parseFloat(document.getElementById('calc_stoppage_pct').value) || 0;

    const grossHakedis = contractVal * (progressPct / 100);
    const stoppageAmt = grossHakedis * (stoppagePct / 100);
    const retentionAmt = grossHakedis * (retentionPct / 100);
    const netPayment = grossHakedis - stoppageAmt - retentionAmt;

    const fmt = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' });

    document.getElementById('calc_pct_display').textContent = `%${progressPct} Tamamlanan`;
    document.getElementById('res_gross_hakedis').textContent = fmt.format(grossHakedis);
    document.getElementById('res_stoppage_amt').textContent = fmt.format(stoppageAmt);
    document.getElementById('res_retention_amt').textContent = fmt.format(retentionAmt);
    document.getElementById('res_net_payment').textContent = fmt.format(netPayment);
}

// Run calculator and search setup on initial load
document.addEventListener('DOMContentLoaded', function() {
    runContractCalc();

    // Table real-time search filter
    const searchInput = document.getElementById('contractSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#contractsTable tbody tr');
            rows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
