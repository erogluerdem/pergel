@extends('layouts.master')

@section('title', 'Tapu & Gayrimenkul Resmi Evrak Portföyü')

@push('styles')
<style>
    .pd-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .pd-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: pdGlow 4s linear infinite;
    }

    @keyframes pdGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .pd-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-pd {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-pd:hover {
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

    .custom-input-pd {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 8px 12px !important;
    }

    .custom-input-pd:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .custom-pd-table thead,
    .custom-pd-table thead tr,
    .custom-pd-table thead th {
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

    .custom-pd-table td {
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
    <div class="pd-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill pd-badge-pill">
                        <i class="ri-shield-keyhole-fill text-warning fs-5"></i> GAYRİMENKUL & TAPU YÖNETİMİ
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $documents->total() }} Kayıtlı Belge
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Tapu & Gayrimenkul Resmi Evrak Portföyü</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Proje ve mülklere ait tapu, mesken iskan, imar durum çapı, yapı ruhsatı ve abonelik evraklarının dijital arşiv takibi.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('property_documents.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="ri-add-circle-fill fs-5"></i>+ Yeni Resmi Belge Ekle
                </a>
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
            <div class="card custom-kpi-card-pd h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM KAYITLI BELGE</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $documents->total() }} Kayıt</div>
                        <small class="text-primary fw-bold"><i class="ri-file-copy-2-line me-1"></i>Tüm Evrak Portföyü</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-file-copy-2-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-pd h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">İŞLENMİŞ & GEÇERLİ TAPULAR</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ \App\Models\PropertyDocument::where('status', 'processed')->count() }} Kayıt</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Tamamlanmış Yasal Onaylar</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-pd h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">SÜRESİ YAKLAŞAN / YENİLEME</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ \App\Models\PropertyDocument::where('status', 'expired')->orWhere('requires_renewal', true)->count() }} Kayıt</div>
                        <small class="text-warning fw-bold"><i class="ri-alarm-warning-fill me-1"></i>Yenileme Gerektiren</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-alarm-warning-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-pd h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">EVRAK HARÇ & MALİYET</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.5rem;">₺{{ number_format(\App\Models\PropertyDocument::sum('document_cost'), 0, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-coins-line me-1"></i>Resmi Harç Toplamı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-coins-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER CARD -->
    <div class="card custom-card-container mb-4">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-filter-3-line text-warning fs-5"></i>Evrak Filtreleme Modülü
            </h5>
        </div>
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-extrabold text-dark small">Belge Türü</label>
                    <select name="type" class="form-select custom-input-pd">
                        <option value="">-- Tüm Belge Türleri --</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-extrabold text-dark small">Durum</label>
                    <select name="status" class="form-select custom-input-pd">
                        <option value="">-- Tüm Durumlar --</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-extrabold text-dark small">Proje</label>
                    <select name="project_id" class="form-select custom-input-pd">
                        <option value="">-- Tüm Projeler --</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>🏗️ {{ $proj->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-warning text-dark fw-extrabold rounded-pill w-100 py-2 shadow-sm">
                        <i class="ri-search-line me-1"></i>Filtrele
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-folder-shield-2-line text-warning fs-5"></i>Resmi Evrak Cetveli
            </h5>
            <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                {{ $documents->total() }} Belge
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-pd-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Belge Türü</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Belge Başlığı & No</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Proje / Mülk</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Düzenleyen Kurum</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Tarihler</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Harç / Maliyet</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Durum</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            <tr>
                                <td class="ps-4 py-3 font-monospace">
                                    @if($doc->document_type == 'tapu')
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">📜 Tapu Belgesi</span>
                                    @elseif($doc->document_type == 'mesken')
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">🏠 İskan / Mesken</span>
                                    @elseif($doc->document_type == 'imar')
                                        <span class="badge bg-info text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">📐 İmar Durumu</span>
                                    @elseif($doc->document_type == 'yapı_ruhsat')
                                        <span class="badge bg-purple text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">🏗️ Yapı Ruhsatı</span>
                                    @elseif($doc->document_type == 'elektrik')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">⚡ Elektrik</span>
                                    @elseif($doc->document_type == 'su')
                                        <span class="badge bg-cyan text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">💧 Su Tesisatı</span>
                                    @elseif($doc->document_type == 'doğalgaz')
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">🔥 Doğalgaz</span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">📄 {{ $doc->getTypeLabel() }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-dark d-block fs-6 fw-extrabold">{{ $doc->title }}</strong>
                                    <small class="text-muted font-monospace fw-bold"><i class="ri-hashtag me-1"></i>{{ $doc->document_number ?? 'Belge No Yok' }}</small>
                                </td>
                                <td>
                                    @if($doc->project)
                                        <span class="badge bg-dark text-warning border border-warning rounded-pill px-3 py-1 fw-bold"><i class="ri-building-line me-1"></i>{{ $doc->project->name }}</span>
                                    @elseif($doc->asset)
                                        <span class="badge bg-light text-dark border rounded-pill px-3 py-1 fw-bold"><i class="ri-community-line me-1"></i>{{ $doc->asset->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-extrabold text-dark">🏢 {{ $doc->issued_by ?? 'Kurum Girilmedi' }}</span>
                                </td>
                                <td class="font-monospace">
                                    <small class="d-block text-muted">Veriliş: <strong class="text-dark">{{ $doc->issue_date ? $doc->issue_date->format('d.m.Y') : '-' }}</strong></small>
                                    @if($doc->expiry_date)
                                        @php $days = now()->diffInDays($doc->expiry_date, false); @endphp
                                        <small class="d-block">
                                            Bitiş: <strong class="{{ $days < 0 ? 'text-danger' : ($days < 30 ? 'text-warning' : 'text-success') }}">{{ $doc->expiry_date->format('d.m.Y') }}</strong>
                                        </small>
                                    @endif
                                </td>
                                <td class="text-end font-monospace">
                                    @if($doc->document_cost > 0)
                                        <strong class="text-success font-monospace fs-6">₺{{ number_format($doc->document_cost, 0, ',', '.') }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center font-monospace">
                                    @if($doc->status == 'processed')
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">✓ İŞLENDİ</span>
                                    @elseif($doc->status == 'received')
                                        <span class="badge bg-info text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">📥 ALINDI</span>
                                    @elseif($doc->status == 'expired')
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #ef4444 !important;">❌ SÜRESİ DOLDU</span>
                                    @else
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">⏳ BEKLEMEDE</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('property_documents.show', $doc->id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="İncele">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('property_documents.edit', $doc->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('property_documents.destroy', $doc->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'Resmi Evrak Kaydını Sil?', 'Bu tapu/evrak kaydı silinecektir.')" title="Sil">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="ri-folder-open-line display-4 text-warning mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Belge Kaydı Bulunmuyor</h6>
                                    <p class="small text-muted mb-0">Üstteki "+ Yeni Resmi Belge Ekle" butonuna tıklayarak ilk tapu/evrak kaydınızı ekleyebilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-3 d-flex justify-content-center">
            {{ $documents->links() }}
        </div>
    </div>

</div>
@endsection
