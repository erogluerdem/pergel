@extends('layouts.master')

@section('title', 'Arsa/Emlak Yönetimi')

@push('styles')
<style>
    .prop-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .prop-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: propGlow 4s linear infinite;
    }

    @keyframes propGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .prop-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-prop {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-prop:hover {
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

    .custom-input-prop {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 8px 12px !important;
    }

    .custom-input-prop:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .custom-prop-table thead,
    .custom-prop-table thead tr,
    .custom-prop-table thead th {
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

    .custom-prop-table td {
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

    @php
        $totalPropsCount = method_exists($properties, 'total') ? $properties->total() : count($properties ?? []);
        $totalLandValue = \App\Models\Property::sum('land_value') ?? 0;
        $clearLegalCount = \App\Models\Property::where('legal_status', 'clear')->count();
        $vacantCount = \App\Models\Property::where('status', 'vacant')->count();
    @endphp

    <!-- HERO HEADER BANNER -->
    <div class="prop-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill prop-badge-pill">
                        <i class="ri-map-pin-2-fill text-warning fs-5"></i> OPERASYON & ARSA EMLAK PORTALI
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $totalPropsCount }} Arsa Kaydı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Arsa & Emlak Portföy Yönetimi</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Şirket portföyündeki tüm arsaları, tapu yasal durumlarını, risk seviyelerini ve ekspertiz değerlerini anlık takip edin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                @can('create', App\Models\Property::class)
                    <a href="{{ route('operations.properties.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Arsa Ekle
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ $message }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prop h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM ARSA</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $totalPropsCount }} Kayıt</div>
                        <small class="text-primary fw-bold"><i class="ri-map-2-line me-1"></i>Portföy Arsa Varlığı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-map-pin-2-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prop h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">YASAL SORUNSUZ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $clearLegalCount }} Arsa</div>
                        <small class="text-success fw-bold"><i class="ri-shield-check-fill me-1"></i>Temiz Tapu Durumu</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-shield-check-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prop h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">BOŞ / UYGUN ARSALAR</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ $vacantCount }} Arsa</div>
                        <small class="text-warning fw-bold"><i class="ri-time-line me-1"></i>Projelendirilebilir</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-landscape-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prop h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM ARSA DEĞERİ</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.5rem;">₺{{ number_format($totalLandValue, 0, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Toplam Ekspertiz Hacmi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER CARD -->
    <div class="card custom-card-container mb-4">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-filter-3-line text-warning fs-5"></i>Arsa Filtreleme & Arama Modülü
            </h5>
        </div>
        <div class="card-body p-4">
            <form method="GET" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label fw-extrabold text-dark small">Proje</label>
                    <select name="project_id" class="form-select custom-input-prop">
                        <option value="">-- Tümü --</option>
                        @foreach ($projects ?? [] as $projectId => $projectName)
                            <option value="{{ $projectId }}" {{ request('project_id') == $projectId ? 'selected' : '' }}>
                                🏗️ {{ $projectName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-extrabold text-dark small">Şehir</label>
                    <input type="text" name="city" class="form-control custom-input-prop" placeholder="Örn: İstanbul" value="{{ request('city') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-extrabold text-dark small">İlçe</label>
                    <input type="text" name="district" class="form-control custom-input-prop" placeholder="Örn: Kadıköy" value="{{ request('district') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-extrabold text-dark small">Durum</label>
                    <select name="status" class="form-select custom-input-prop">
                        <option value="">-- Tümü --</option>
                        @foreach ($statuses ?? [] as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-extrabold text-dark small">Yasal Durum</label>
                    <select name="legal_status" class="form-select custom-input-prop">
                        <option value="">-- Tümü --</option>
                        @foreach ($legalStatuses ?? [] as $value => $label)
                            <option value="{{ $value }}" {{ request('legal_status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-extrabold text-dark small">Risk Seviyesi</label>
                    <select name="investment_risk_level" class="form-select custom-input-prop">
                        <option value="">-- Tümü --</option>
                        @foreach ($riskLevels ?? [] as $value => $label)
                            <option value="{{ $value }}" {{ request('investment_risk_level') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-9">
                    <label class="form-label fw-extrabold text-dark small">Arama (Kod, İsim, Tapu No)</label>
                    <input type="text" name="search" class="form-control custom-input-prop" placeholder="Arsa Kodu, Parsel Adı, Tapu Pafta No..." value="{{ request('search') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-warning text-dark fw-extrabold rounded-pill w-100 py-2 shadow-sm">
                        <i class="ri-search-line me-1"></i>Filtrele & Ara
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-map-2-line text-warning fs-5"></i>Arsa Kayıt Listesi Cetveli
            </h5>
            <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                {{ $totalPropsCount }} Kayıt
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-prop-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Arsa Kodu</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Arsa Adı & Adres</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Proje</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Şehir / İlçe</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Durum</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Yasal Durum</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Arsa Değeri (₺)</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($properties ?? [] as $property)
                            <tr>
                                <td class="ps-4 py-3">
                                    <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-extrabold" style="font-size: 11px;">
                                        {{ $property->property_code }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-extrabold text-dark fs-6">📍 {{ $property->name }}</div>
                                    <small class="text-muted fw-semibold">{{ $property->address ?? '-' }}</small>
                                </td>
                                <td class="fw-extrabold text-dark fs-6">
                                    {{ $property->project?->name ?? 'Projesiz' }}
                                </td>
                                <td class="fw-bold text-dark fs-6">
                                    🏛️ {{ $property->city ?? '-' }} {{ $property->district ? ' / ' . $property->district : '' }}
                                </td>
                                <td class="text-center font-monospace">
                                    <span class="badge {{ $property->status == 'vacant' ? 'bg-warning text-dark' : ($property->status == 'acquired' ? 'bg-info text-white' : ($property->status == 'developed' ? 'bg-success text-white' : 'bg-secondary text-white')) }} rounded-pill px-3 py-1.5 font-monospace fw-bold" style="{{ $property->status == 'developed' ? 'background: #16a34a !important;' : '' }}">
                                        {{ $statuses[$property->status] ?? $property->status }}
                                    </span>
                                </td>
                                <td class="text-center font-monospace">
                                    <span class="badge {{ $property->legal_status == 'clear' ? 'bg-success text-white' : ($property->legal_status == 'disputed' ? 'bg-danger text-white' : 'bg-warning text-dark') }} rounded-pill px-3 py-1.5 font-monospace fw-bold" style="{{ $property->legal_status == 'clear' ? 'background: #16a34a !important;' : ($property->legal_status == 'disputed' ? 'background: #ef4444 !important;' : '') }}">
                                        {{ $legalStatuses[$property->legal_status] ?? $property->legal_status }}
                                    </span>
                                </td>
                                <td class="text-end font-monospace fw-extrabold text-success fs-6">
                                    {{ $property->land_value ? '₺' . number_format($property->land_value, 0, ',', '.') : '-' }}
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('operations.properties.show', $property) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="Görüntüle">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        @can('update', $property)
                                            <a href="{{ route('operations.properties.edit', $property) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $property)
                                            <form method="POST" action="{{ route('operations.properties.destroy', $property) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'Arsa/Emlak Kaydını Sil?', 'Bu arsa/emlak kaydı silinecektir.')" title="Sil">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="ri-map-pin-line display-4 text-warning mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Henüz Arsa Kaydı Bulunmuyor</h6>
                                    <p class="small text-muted mb-0">Üstteki "+ Yeni Arsa Ekle" butonuna tıklayarak ilk arsa kaydınızı oluşturabilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if (($properties ?? collect())->count() > 0 && method_exists($properties, 'links'))
            <div class="card-footer bg-transparent border-0 py-3 d-flex justify-content-center">
                {{ $properties->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
