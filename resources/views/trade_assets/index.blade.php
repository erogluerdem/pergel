@extends('layouts.master')

@section('title', 'Takas Varlıkları & Gayrimenkul Takası Yönetimi')

@push('styles')
<style>
    .asset-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .asset-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: assetGlow 4s linear infinite;
    }

    @keyframes assetGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .asset-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-asset {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-asset:hover {
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

    .custom-input-asset {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 9px 13px !important;
    }

    .custom-input-asset:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .form-label-custom {
        font-weight: 800 !important;
        color: #0f172a !important;
        font-size: 0.85rem !important;
        margin-bottom: 5px !important;
    }

    .custom-asset-table thead,
    .custom-asset-table thead tr,
    .custom-asset-table thead th {
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

    .custom-asset-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 600 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="asset-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill asset-badge-pill">
                        <i class="ri-repeat-2-fill text-warning fs-5"></i> SATIŞ TAKAS PORTFÖYÜ & ENVANTER YÖNETİMİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        {{ $assets->total() }} Kayıtlı Takas Varlığı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Takas Varlıkları & Gayrimenkul Takası</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Konut satışları karşılığında müşterilerden alınan araç, arsa, daire ve ikincil takas varlıklarının ekspertiz değerleri, sayılan tutarları ve envanter stok durumları.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-warning text-dark px-4 py-3 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm fs-6 font-monospace">
                    <i class="ri-exchange-box-fill fs-4"></i>Toplam Takas Hacmi: ₺{{ number_format($assets->sum('agreed_amount'), 0, ',', '.') }}
                </span>
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

    @php
        $totalAgreed = $assets->sum('agreed_amount');
        $inStockValue = $assets->where('status', 'in_inventory')->sum('agreed_amount');
        $soldValue = $assets->where('status', 'sold')->sum('agreed_amount');
        $transferredCount = $assets->where('status', 'transferred')->count();
    @endphp

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-asset h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM TAKAS HACMİ</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.6rem;">₺{{ number_format($totalAgreed, 2, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-money-dollar-box-line me-1"></i>Sayılan Varlık Tutarı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-exchange-dollar-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-asset h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">STOKTAKİ VARLIKLAR</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($inStockValue, 2, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-store-2-fill me-1"></i>Şirket Envanterinde</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-car-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-asset h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">NAKTE ÇEVRİLEN (SATILAN)</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.6rem;">₺{{ number_format($soldValue, 2, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Likide Dönüştürüldü</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-currency-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-asset h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">DEVREDİLEN VARLIKLAR</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ $transferredCount }} Varlık</div>
                        <small class="text-warning fw-bold"><i class="ri-user-shared-fill me-1"></i>Tedarikçiye Verildi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-user-shared-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="row g-4 mb-5">
        <!-- ADD ASSET FORM COLUMN -->
        <div class="col-lg-4">
            <div class="card custom-card-container h-100">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-add-circle-fill text-warning fs-5"></i>Yeni Takas Varlığı Ekle
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('trade_assets.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label-custom">MÜŞTERİ SEÇİNİZ</label>
                            <select name="customer_id" class="form-select custom-input-asset">
                                <option value="">Müşteri Seçiniz...</option>
                                @foreach($customers as $id => $name)
                                    <option value="{{ $id }}">👤 {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">PROJE SEÇİNİZ</label>
                            <select name="project_id" class="form-select custom-input-asset">
                                <option value="">Proje Seçiniz...</option>
                                @foreach($projects as $id => $name)
                                    <option value="{{ $id }}">🏗️ {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">TAKAS VARLIK TÜRÜ <span class="text-danger">*</span></label>
                            <select name="asset_type" class="form-select custom-input-asset" required>
                                <option value="vehicle" selected>🚗 Motorlu Araç (Otomobil, SUV, Ticari)</option>
                                <option value="land">🏞️ Arsa / Tarla / Parsel</option>
                                <option value="apartment">🏢 Daire / İşyeri / Dükkan</option>
                                <option value="other">📦 Diğer Takas Varlığı</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">VARLIK BAŞLIĞI / PLAKA / LOKASYON <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control custom-input-asset" placeholder="Örn: 2024 Mercedes C200d (Plaka 34 ABC 123)" required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label-custom">EKSPERTİZ (TL) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="valuation_amount" class="form-control custom-input-asset font-monospace" placeholder="2400000" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label-custom">SAYILAN TUTAR (TL) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="agreed_amount" class="form-control custom-input-asset font-monospace text-success fw-bold" placeholder="2300000" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">STOK / ENVANTER DURUMU <span class="text-danger">*</span></label>
                            <select name="status" class="form-select custom-input-asset" required>
                                <option value="in_inventory" selected>Stokta (Şirket Envanterinde)</option>
                                <option value="sold">Satıldı (Nakte Çevrildi)</option>
                                <option value="transferred">Tedarikçiye / Taşerona Devredildi</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-warning text-dark rounded-pill py-2.5 w-100 fw-extrabold shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <i class="ri-save-fill fs-5"></i>Takas Varlığını Kaydet
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TABLE COLUMN -->
        <div class="col-lg-8">
            <div class="card custom-card-container h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-list-check-2 text-warning fs-5"></i>Takas Varlıkları Envanteri
                    </h5>
                    <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">{{ $assets->total() }} Varlık</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table custom-asset-table align-middle mb-0">
                            <thead style="background-color: #0f172a !important;">
                                <tr style="background-color: #0f172a !important;">
                                    <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Tür</th>
                                    <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Varlık Tanımı</th>
                                    <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Müşteri</th>
                                    <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Ekspertiz</th>
                                    <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Anlaşılan Tutar</th>
                                    <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Stok Durumu</th>
                                    <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assets as $asset)
                                    <tr>
                                        <td class="ps-4 py-3 font-monospace">
                                            @if($asset->asset_type === 'vehicle')
                                                <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">🚗 Araç</span>
                                            @elseif($asset->asset_type === 'land')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">🏞️ Arsa</span>
                                            @elseif($asset->asset_type === 'apartment')
                                                <span class="badge bg-info text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">🏢 Daire</span>
                                            @else
                                                <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">📦 Diğer</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="fw-extrabold text-dark d-block fs-6">{{ $asset->title }}</strong>
                                            <small class="text-muted fw-bold">{{ $asset->project->name ?? 'Takas Portföyü' }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-extrabold text-dark">👤 {{ $asset->customer->name ?? '-' }}</span>
                                        </td>
                                        <td class="text-end font-monospace">
                                            <span class="text-muted font-monospace fw-bold">₺{{ number_format($asset->valuation_amount, 2, ',', '.') }}</span>
                                        </td>
                                        <td class="text-end font-monospace">
                                            <strong class="text-success font-monospace fs-6 fw-extrabold">₺{{ number_format($asset->agreed_amount, 2, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-center font-monospace">
                                            @if($asset->status === 'in_inventory')
                                                <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">✓ STOKTA</span>
                                            @elseif($asset->status === 'sold')
                                                <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">🛒 SATILDI (NAKİT)</span>
                                            @else
                                                <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">👤 DEVREDİLDİ</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('trade_assets.destroy', $asset->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'Takas Varlığını Sil?', 'Bu takas kaydı silinecektir.')" title="Sil">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="ri-repeat-2-line display-4 text-warning mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Takas Varlığı Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan konut satışında takasa alınan araç, arsa veya gayrimenkul bilgilerini girerek ilk takas varlığı kaydınızı oluşturabilirsiniz.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 d-flex justify-content-center">
                        {{ $assets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
