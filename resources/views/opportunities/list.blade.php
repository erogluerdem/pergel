@extends('layouts.master')

@section('title', 'Potansiyel Projeler & CRM Fırsat Portalı - Liste')

@push('styles')
<style>
    .opp-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .opp-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: oppGlow 4s linear infinite;
    }

    @keyframes oppGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .opp-badge {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-opp {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-opp:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-input-opp {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.88rem !important;
    }
    .custom-input-opp:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .opp-table-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        overflow: hidden;
    }

    .opp-table thead,
    .opp-table thead tr,
    .opp-table thead th,
    .opp-table th {
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

    .opp-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 600 !important;
    }

    .opp-table tbody tr {
        transition: background-color 0.18s ease;
    }

    .opp-table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .owner-avatar-pill {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #0f172a;
        color: #fef08a;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        border: 2px solid #d97706;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="opp-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill opp-badge">
                        <i class="ri-lightbulb-fill text-warning fs-5"></i> CRM & POTANSİYEL PROJELER (FIRSAT YÖNETİMİ)
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $totalOpportunities ?? count($opportunities) }} Aktif Fırsat
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Potansiyel Projeler & Fırsat Portföyü</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Müşteri ve yatırımcı ilişkilerinizi, potansiyel ihale değerlerinizi ve kazanma olasılıklarınızı merkezi CRM portalından yönetin.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('opportunities.kanban') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-kanban-view fs-5"></i>Kanban Görünümü
                    </a>
                    <a href="{{ route('opportunities.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Fırsat Ekle
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-opp h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM FIRSAT SAYISI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $totalOpportunities ?? 0 }} Fırsat</div>
                        <small class="text-warning fw-bold"><i class="ri-rocket-fill me-1"></i>CRM Takibindeki Projeler</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-lightbulb-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-opp h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM POTANSİYEL DEĞER</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.5rem;">₺{{ number_format($totalValue ?? 0, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>KDV Dahil: ₺{{ number_format($totalValueKdv ?? 0, 0, ',', '.') }}</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-coins-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-opp h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KAZANILAN PROJELER</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $wonOpportunities ?? 0 }} Proje</div>
                        <small class="text-info fw-bold"><i class="ri-trophy-fill me-1"></i>Sözleşmeye Dönüşen</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-trophy-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-opp h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ORTALAMA OLASILIK</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">%{{ number_format($avgProbability ?? 0, 1) }}</div>
                        <small class="text-primary fw-bold"><i class="ri-percent-fill me-1"></i>Kazanma İhtimali</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-pie-chart-2-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- INDEX TABLE CONTAINER -->
    <div class="opp-table-container shadow-sm mb-4">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-table-line text-warning fs-5"></i>Potansiyel Projeler & Fırsat Cetveli
                </h5>
                <a href="{{ route('opportunities.export') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold">
                    <i class="ri-file-excel-2-line me-1"></i>CSV İndir
                </a>
            </div>

            <!-- FILTER FORM ROW -->
            <form method="GET" action="{{ route('opportunities.list') }}" class="row g-2">
                <div class="col-md-3">
                    <input name="search" value="{{ request('search') }}" class="form-control custom-input-opp rounded-pill py-1.5" placeholder="Fırsat adı veya müşteri ara...">
                </div>
                <div class="col-md-2">
                    <select name="stage" class="form-select custom-input-opp rounded-pill py-1.5">
                        <option value="">Tüm Aşamalar</option>
                        @foreach($stages as $k => $v)
                            <option value="{{ $k }}" {{ request('stage') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="owner" class="form-select custom-input-opp rounded-pill py-1.5">
                        <option value="">Tüm Sorumlular</option>
                        @foreach($owners as $o)
                            <option value="{{ $o->id }}" {{ request('owner') == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input name="value_min" value="{{ request('value_min') }}" type="number" class="form-control custom-input-opp rounded-pill py-1.5" placeholder="Min Değer ₺">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 py-1.5 fw-extrabold shadow-sm w-100">
                        <i class="ri-filter-3-line me-1"></i>Filtrele
                    </button>
                    <a href="{{ route('opportunities.list') }}" class="btn btn-outline-light rounded-pill px-3 py-1.5 fw-bold">
                        Sıfırla
                    </a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table opp-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Fırsat Adı & Detay</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Müşteri / Yatırımcı</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Aşama</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Tahmini Değer</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">KDV Dahil</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Olasılık</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Sorumlu</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Hedef Kapanış</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Aksiyonlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($opportunities as $o)
                            @php
                                $est = (float)($o->estimated_value ?: 0);
                                $estKdv = !is_null($o->estimated_value_kdv) ? $o->estimated_value_kdv : ($est * 1.20);
                                $stageBadges = [
                                    'lead' => 'bg-secondary text-white',
                                    'qualification' => 'bg-info text-white',
                                    'proposal' => 'bg-warning text-dark',
                                    'negotiation' => 'bg-primary text-white',
                                    'won' => 'bg-success text-white',
                                    'lost' => 'bg-danger text-white'
                                ];
                                $prob = intval($o->probability ?? 0);
                            @endphp
                            <tr>
                                <td class="ps-4 py-3" style="min-width: 240px;">
                                    <a href="{{ route('opportunities.show', $o->id) }}" class="fw-extrabold text-dark fs-6 text-decoration-none d-block">
                                        {{ $o->title }}
                                    </a>
                                    <small class="text-secondary fw-semibold">{{ Str::limit($o->description ?? '', 65) }}</small>
                                </td>
                                <td class="fw-bold text-dark">
                                    🏢 {{ $o->customer ?? 'Belirtilmemiş' }}
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-1.5 fw-bold {{ $stageBadges[$o->stage] ?? 'bg-secondary text-white' }}" style="font-size: 11px;">
                                        {{ $stages[$o->stage] ?? ucfirst($o->stage) }}
                                    </span>
                                </td>
                                <td class="text-end font-monospace fw-extrabold fs-6 text-dark">
                                    ₺{{ number_format($est, 0, ',', '.') }}
                                </td>
                                <td class="text-end font-monospace fw-extrabold fs-6 text-success">
                                    ₺{{ number_format($estKdv, 0, ',', '.') }}
                                </td>
                                <td class="text-center font-monospace">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <div class="progress flex-grow-1" style="height: 8px; max-width: 60px; background: #e2e8f0;">
                                            <div class="progress-bar {{ $prob >= 70 ? 'bg-success' : ($prob >= 40 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $prob }}%;"></div>
                                        </div>
                                        <span class="fw-extrabold text-dark ms-1" style="font-size: 11px;">%{{ $prob }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($o->owner)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="owner-avatar-pill">
                                                {{ strtoupper(substr($o->owner->name, 0, 1)) }}
                                            </div>
                                            <span class="fw-bold text-dark font-size-13">{{ $o->owner->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="font-monospace text-dark fw-bold">
                                    {{ $o->expected_close_date ? \Carbon\Carbon::parse($o->expected_close_date)->format('d.m.Y') : '—' }}
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('opportunities.show', $o->id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="Detay">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('opportunities.edit', $o->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="ri-lightbulb-line display-4 text-warning mb-2 d-block"></i>
                                    <h5 class="fw-bold text-dark">Hiç Potansiyel Fırsat Bulunamadı</h5>
                                    <p class="small text-muted mb-0">Filtre kriterlerinizi sıfırlamayı veya yeni bir fırsat kaydı oluşturmayı deneyin.</p>
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
