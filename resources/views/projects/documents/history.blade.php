@extends('layouts.master')

@section('title', 'Doküman Sürüm Geçmişi — ' . $doc->name)

@section('topbar-title', 'Projeler - Sürüm Geçmişi')

@push('styles')
<style>
    .doc-hist-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .doc-hist-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: docHistGlow 4s linear infinite;
    }

    @keyframes docHistGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .doc-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .doc-version-timeline-custom {
        position: relative;
        padding-left: 2.2rem;
    }

    .doc-version-timeline-custom::before {
        content: '';
        position: absolute;
        left: 14px;
        top: 15px;
        bottom: 15px;
        width: 3.5px;
        background: #cbd5e1;
    }

    .doc-version-item-custom {
        position: relative;
        margin-bottom: 1.8rem;
    }

    .doc-version-node-custom {
        position: absolute;
        left: -2.2rem;
        top: 18px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .version-card-box {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 24px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .version-card-box:hover {
        transform: translateX(4px);
        border-color: #d97706 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="doc-hist-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill doc-badge-pill">
                        <i class="ri-history-fill text-warning fs-5"></i> SÜRÜM VE REVİZYON TARİHÇESİ
                    </span>
                    <span class="badge bg-dark text-warning border border-warning px-3 py-2 rounded-pill font-monospace fw-bold">
                        KOD: {{ $doc->code ?? 'KODSUZ' }}
                    </span>
                    <span class="badge bg-primary text-white px-3 py-2 rounded-pill font-monospace fw-bold">
                        DİSİPLİN: {{ $doc->discipline ?? 'Genel' }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">{{ $doc->name }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Dokümanın ilk oluşturulma anından günümüze kadarki tüm revizyon, sürüm güncellemeleri ve yükleme geçmişi.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('projects.documents.index', $doc->project_id) }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Doküman Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- REVISION TIMELINE STREAM -->
    <div class="row mb-5">
        <div class="col-lg-10 col-xl-9 mx-auto">
            <div class="doc-version-timeline-custom">
                @foreach($chain as $index => $c)
                    <div class="doc-version-item-custom">
                        <div class="doc-version-node-custom {{ $index === 0 ? 'bg-warning text-dark' : 'bg-secondary text-white' }}">
                            <i class="ri-history-line fw-bold"></i>
                        </div>
                        <div class="version-card-box">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge bg-dark text-warning border border-warning rounded-pill px-3 py-1.5 font-monospace fw-bold fs-6">{{ $c->version ?? 'V1.0' }}</span>
                                    @if($index === 0)
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">🟢 En Güncel Aktif Sürüm</span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">📂 Arşivlenmiş Revizyon</span>
                                    @endif
                                </div>
                                <span class="fw-extrabold font-monospace text-dark">
                                    <i class="ri-calendar-event-fill me-1 text-warning"></i>{{ $c->uploaded_at?->format('d.m.Y H:i') ?? $c->created_at->format('d.m.Y H:i') }}
                                </span>
                            </div>

                            <div class="row g-3 my-2 text-dark">
                                <div class="col-sm-4">
                                    <small class="text-muted fw-bold d-block" style="font-size: 11px;">YÜKLEYEN KULLANICI</small>
                                    <span class="fw-extrabold text-primary"><i class="ri-user-3-fill me-1"></i>{{ $c->uploader?->name ?? 'Sistem Yetkilisi' }}</span>
                                </div>
                                <div class="col-sm-4">
                                    <small class="text-muted fw-bold d-block" style="font-size: 11px;">EVRAK DURUMU</small>
                                    <span class="fw-extrabold text-capitalize text-dark">{{ $c->status }}</span>
                                </div>
                                <div class="col-sm-4">
                                    <small class="text-muted fw-bold d-block" style="font-size: 11px;">MIME TİPİ / FORMAT</small>
                                    <span class="fw-extrabold font-monospace text-dark">{{ $c->mime_type ?? 'application/pdf' }}</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top">
                                <a href="{{ route('projects.documents.viewer', [$c->project_id, $c->id]) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-1.5 fw-extrabold shadow-sm">
                                    <i class="ri-eye-fill me-1"></i>Önizle
                                </a>
                                <a href="{{ route('projects.documents.show', [$c->project_id, $c->id]) }}" target="_blank" class="btn btn-outline-dark rounded-pill px-3.5 py-1.5 fw-extrabold">
                                    <i class="ri-download-fill me-1"></i>İndir
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
