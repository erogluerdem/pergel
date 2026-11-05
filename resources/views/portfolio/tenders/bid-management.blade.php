@extends('layouts.master')

@section('title', 'Teklif & İhale Eşitleme (Bid Leveling) Portalı')

@push('styles')
<style>
    .bid-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .bid-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: bidGlow 4s linear infinite;
    }

    @keyframes bidGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .bid-badge {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-bid {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-bid:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-input-bid {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-bid:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .bid-table-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        overflow: hidden;
    }

    .bid-table thead,
    .bid-table thead tr,
    .bid-table thead th,
    .bid-table th {
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

    .bid-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 600 !important;
    }

    .bid-table tbody tr {
        transition: background-color 0.18s ease;
    }

    .bid-table tbody tr:hover {
        background-color: #f8fafc !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="bid-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bid-badge">
                        <i class="ri-file-signature-fill text-warning fs-5"></i> TEKLİF & İHALE EŞİTLEME PORTALI (BID LEVELING)
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $stats['published_count'] ?? 0 }} Canlı İhale
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">İhale Teklif Paketleri & Karşılaştırma</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Şantiye ve taşeron teklif paketlerini yönetin, gelen birim fiyat tekliflerini Poz (BOQ) bazında eşitleyin ve en avantajlı teklifi seçin.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('tenders.package.prepare') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-file-archive-line fs-5"></i>İhale Paketleri
                    </a>
                    <a href="{{ route('tenders.package.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Paket Oluştur
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-bid h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM İHALE PAKETİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_packages'] ?? 0 }} Paket</div>
                        <small class="text-warning fw-bold"><i class="ri-folder-zip-fill me-1"></i>Kayıtlı İhale Portföyü</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-folder-2-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-bid h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">CANLI TEKLİF TOPLANAN</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $stats['published_count'] ?? 0 }} Paket</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Yayındaki İhaleler</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-send-plane-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-bid h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">GELEN TOPLAM TEKLİF</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_offers'] ?? 0 }} Teklif</div>
                        <small class="text-info fw-bold"><i class="ri-auction-fill me-1"></i>Taşeron & Tedarikçi Teklifi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-file-list-3-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-bid h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TAMAMLATILAN / KAPALI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $stats['closed_count'] ?? 0 }} Paket</div>
                        <small class="text-primary fw-bold"><i class="ri-history-fill me-1"></i>Sonuçlanmış İhaleler</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-lock-2-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & TABLE CONTAINER -->
    <div class="bid-table-container shadow-sm mb-4">
        <div class="card-header bg-dark text-white p-3.5 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-table-line text-warning fs-5"></i>İhale Paketleri İndeksi & Leveling Tablosu
            </h5>
            <form class="d-flex align-items-center gap-2" method="GET">
                <input name="search" value="{{ request('search') }}" class="form-control rounded-pill custom-input-bid py-1.5" placeholder="Proje veya paket ara..." />
                <select name="status" class="form-select rounded-pill custom-input-bid py-1.5" style="width: 170px;">
                    <option value="">Tüm Durumlar</option>
                    <option value="draft" {{ request('status')=='draft'? 'selected':'' }}>Taslak</option>
                    <option value="published" {{ request('status')=='published'? 'selected':'' }}>Teklif Toplanıyor</option>
                    <option value="closed" {{ request('status')=='closed'? 'selected':'' }}>Sona Erdi</option>
                </select>
                <button class="btn btn-warning text-dark rounded-pill px-3 py-1.5 fw-extrabold shadow-sm" type="submit">
                    Filtrele
                </button>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table bid-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Paket Adı</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Proje</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Durum</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Bitiş Tarihi</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Teklif Sayısı</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">En Düşük Teklif</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Sorumlu</th>
                            <th class="pe-4 text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Aksiyonlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $p)
                            <tr>
                                <td class="ps-4 py-3" style="min-width: 250px;">
                                    <a href="{{ route('tenders.bid.leveling', $p->id) }}" class="fw-extrabold text-dark fs-6 text-decoration-none">
                                        {{ $p->name }}
                                    </a>
                                    <div class="small text-secondary fw-semibold">{{ Str::limit($p->short_description ?? '', 75) }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-dark text-warning font-monospace px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                        {{ optional($p->project)->name ?? 'Genel Proje' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $status = $p->status ?? 'unknown';
                                    @endphp
                                    @if($status === 'published')
                                        <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">Teklif Toplanıyor</span>
                                    @elseif($status === 'draft')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">Taslak</span>
                                    @elseif($status === 'closed')
                                        <span class="badge bg-dark text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">Sona Erdi</span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">İnceleniyor</span>
                                    @endif
                                </td>
                                <td class="font-monospace text-dark fw-bold">
                                    @if(!empty($p->end_date))
                                        {{ \Carbon\Carbon::parse($p->end_date)->format('d.m.Y') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-end font-monospace fw-extrabold fs-6 text-primary">
                                    {{ number_format($p->offers_count ?? 0, 0, ',', '.') }} Teklif
                                </td>
                                <td class="text-end font-monospace fw-extrabold fs-6 text-success">
                                    @if(!is_null($p->offers_min))
                                        ₺{{ number_format($p->offers_min, 0, ',', '.') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="fw-bold text-dark">{{ optional($p->responsible)->name ?? 'Yönetici' }}</td>
                                <td class="pe-4 text-center">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('tenders.bid.leveling', $p->id) }}" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-extrabold shadow-sm" title="Teklifleri Karşılaştır / Leveling">
                                            <i class="ri-scales-3-line me-1"></i>Karşılaştır
                                        </a>
                                        <a href="{{ route('tenders.package.show', $p->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Paket Detayı">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="ri-file-search-line display-4 text-warning mb-2 d-block"></i>
                                    <h5 class="fw-bold text-dark">Hiç İhale Paketi Bulunamadı</h5>
                                    <p class="small text-muted mb-0">Filtre kriterlerinizi sıfırlamayı veya yeni bir teklif paketi oluşturmayı deneyin.</p>
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