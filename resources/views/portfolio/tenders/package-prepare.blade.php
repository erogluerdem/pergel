@extends('layouts.master')

@section('title', 'İhale Paketi & Şartname Hazırlama Portalı')

@push('styles')
<style>
    .prep-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .prep-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: prepGlow 4s linear infinite;
    }

    @keyframes prepGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .prep-badge {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-prep {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-prep:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-input-prep {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-prep:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .prep-table-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        overflow: hidden;
    }

    .prep-table thead,
    .prep-table thead tr,
    .prep-table thead th,
    .prep-table th {
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

    .prep-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 600 !important;
    }

    .prep-table tbody tr {
        transition: background-color 0.18s ease;
    }

    .prep-table tbody tr:hover {
        background-color: #f8fafc !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="prep-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill prep-badge">
                        <i class="ri-file-archive-fill text-warning fs-5"></i> ŞANTİYE İHALE & SATIN ALMA HAZIRLIK PORTALI
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $stats['published_count'] ?? 0 }} Yayında İhale
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">İhale Paketi & Şartname Hazırlama</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Metraj cetvellerini (BOQ), idari/teknik şartnameleri ve tedarikçi davet listelerini hazırlayarak ihalelerinizi yayına alın.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('tenders.bid.management') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-scales-3-line fs-5"></i>Teklif Eşitleme (Leveling)
                    </a>
                    <a href="{{ route('tenders.package.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni İhale Paketi Oluştur
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

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prep h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM İHALE PAKETİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_packages'] ?? 0 }} Paket</div>
                        <small class="text-warning fw-bold"><i class="ri-folder-archive-fill me-1"></i>Hazırlanan İhale Portföyü</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-file-archive-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prep h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">YAYINDAKİ İHALELER</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $stats['published_count'] ?? 0 }} Paket</div>
                        <small class="text-success fw-bold"><i class="ri-broadcast-fill me-1"></i>Aktif İhale İlanları</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-send-plane-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prep h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TASLAK HAZIRLIKLAR</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['draft_count'] ?? 0 }} Paket</div>
                        <small class="text-info fw-bold"><i class="ri-edit-circle-fill me-1"></i>Şartname/Metraj Aşamasında</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-draft-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-prep h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TAHMİNİ İHALE BÜTÇESİ</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.5rem;">₺{{ number_format($stats['total_estimated'] ?? 0, 0, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-calculator-line me-1"></i>Toplam Yaklaşık Maliyet</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-coins-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- INDEX TABLE CONTAINER -->
    <div class="prep-table-container shadow-sm mb-4">
        <div class="card-header bg-dark text-white p-3.5 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-table-line text-warning fs-5"></i>İhale Paketleri & Şartname İndeksi
            </h5>
            <div class="d-flex align-items-center gap-2" style="max-width: 320px; width: 100%;">
                <input type="text" id="prepSearchInput" class="form-control rounded-pill custom-input-prep py-1.5" placeholder="Paket adı veya proje ara...">
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table prep-table align-middle mb-0" id="prepTable">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Paket Adı / Kod</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İlişkili Proje</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Durum</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Teklif Bitiş Tarihi</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Davetli / Teklif</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Sorumlu Yönetici</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Aksiyonlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $p)
                            <tr>
                                <td class="ps-4 py-3">
                                    <strong class="text-dark fs-6 d-block">{{ $p->name }}</strong>
                                    <small class="badge bg-warning text-dark font-monospace fw-extrabold" style="font-size: 10.5px;">MP-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-dark text-warning font-monospace px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                        {{ optional($p->project)->name ?? 'Genel Proje' }}
                                    </span>
                                </td>
                                <td>
                                    @if(($p->status ?? '') === 'published')
                                        <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">Yayında</span>
                                    @elseif(($p->status ?? '') === 'draft')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">Taslak</span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">{{ ucfirst($p->status ?? 'İnceleniyor') }}</span>
                                    @endif
                                </td>
                                <td class="font-monospace text-dark fw-bold">
                                    {{ $p->end_date ? \Carbon\Carbon::parse($p->end_date)->format('d.m.Y') : '—' }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11.5px;">
                                        {{ isset($p->bidders_count) ? $p->bidders_count : '0' }} Davetli / {{ isset($p->offers_count) ? $p->offers_count : '0' }} Teklif
                                    </span>
                                </td>
                                <td class="fw-bold text-dark">
                                    {{ optional($p->responsible)->name ?? (optional($p->project)->project_manager ?? 'İhale Yöneticisi') }}
                                </td>
                                <td class="pe-4 text-end">
                                    @php 
                                        $previewUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('tenders.package.access', now()->addDays(7), ['id'=>$p->id]); 
                                    @endphp
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('tenders.package.create') }}?copy={{ $p->id }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <a href="{{ $previewUrl }}" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-extrabold shadow-sm" target="_blank" title="Tedarikçi Portalı Önizleme">
                                            <i class="ri-external-link-line me-1"></i>Önizle
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="ri-file-archive-line display-4 text-warning mb-2 d-block"></i>
                                    <h5 class="fw-bold text-dark">Henüz İhale Paketi Oluşturulmadı</h5>
                                    <p class="small text-muted mb-0">Sağ üstteki "+ Yeni İhale Paketi Oluştur" butonuna tıklayarak ilk paketinizi hazırlayabilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('prepSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var filter = this.value.toLowerCase();
            var rows = document.querySelectorAll('#prepTable tbody tr');
            rows.forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
@endsection
