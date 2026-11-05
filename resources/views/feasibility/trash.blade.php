@extends('layouts.master')

@section('title', 'Fizibilite - Çöp Kutusu')

@push('styles')
<style>
    .feas-trash-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .feas-trash-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #ef4444, #2563eb, #d97706);
        background-size: 300% 100%;
        animation: feasTrashGlow 4s linear infinite;
    }

    @keyframes feasTrashGlow {
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

    .custom-kpi-card-trash {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-card-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
    }

    .custom-trash-table thead,
    .custom-trash-table thead tr,
    .custom-trash-table thead th {
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

    .custom-trash-table td {
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
    <div class="feas-trash-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill feas-badge-pill">
                        <i class="ri-delete-bin-fill text-warning fs-5"></i> FİZİBİLİTE & SERMAYE
                    </span>
                    <span class="badge bg-danger text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #ef4444 !important;">
                        Çöp Kutusu & Geri Dönüşüm
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Silinen Fizibilite Senaryoları</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Silinmiş fizibilite senaryolarını tek tıklamayla sisteme geri yükleyin veya veri tabanından kalıcı olarak temizleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('feasibility.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Fizibilite Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-trash h-100 border-start border-danger border-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">SİLİNEN SENARYO SAYISI</small>
                <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.8rem;">{{ $studies->count() }} Kayıt</div>
                <small class="text-danger fw-bold"><i class="ri-delete-bin-line me-1"></i>Çöp Kutusundaki Öğeler</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-trash h-100 border-start border-primary border-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">GERİ YÜKLENEBİLİR ÖĞELER</small>
                <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $studies->count() }} Senaryo</div>
                <small class="text-primary fw-bold"><i class="ri-refresh-line me-1"></i>Kurtarılabilir Durumda</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-trash h-100 border-start border-warning border-4">
                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">GÜVENLİ DEPOLAMA</small>
                <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">Soft-Delete</div>
                <small class="text-warning fw-bold"><i class="ri-shield-check-line me-1"></i>Kazaen Silinmeye Karşı Korumalı</small>
            </div>
        </div>
    </div>

    <!-- TRASH TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-delete-bin-7-line text-warning fs-5"></i>Silinmiş Fizibiliteler Listesi
            </h5>
        </div>

        <div class="card-body p-0">
            @if($studies->isEmpty())
                <div class="text-center py-5">
                    <i class="ri-delete-bin-line display-3 text-muted mb-2 d-block"></i>
                    <h5 class="fw-extrabold text-dark mb-1">Çöp Kutusu Boş</h5>
                    <p class="text-muted mb-3">Silinmiş herhangi bir fizibilite senaryosu bulunmamaktadır.</p>
                    <a href="{{ route('feasibility.index') }}" class="btn btn-warning text-dark fw-extrabold rounded-pill px-4 py-2">
                        Fizibilite Listesine Git
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table custom-trash-table align-middle mb-0">
                        <thead style="background-color: #0f172a !important;">
                            <tr style="background-color: #0f172a !important;">
                                <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Senaryo Adı</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Silinme Tarihi</th>
                                <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studies as $s)
                                <tr>
                                    <td class="ps-4 py-3 fw-extrabold text-dark">
                                        📄 {{ $s->name }}
                                    </td>
                                    <td class="font-monospace text-dark fw-semibold" style="font-size: 13px;">
                                        📅 {{ $s->deleted_at ? $s->deleted_at->format('d.m.Y H:i') : '—' }}
                                    </td>
                                    <td class="pe-4 text-end">
                                        <form method="POST" action="{{ route('feasibility.restore', $s->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 fw-bold me-1">
                                                <i class="ri-refresh-line me-1"></i>Geri Yükle
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('feasibility.force_delete', $s->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3 py-1.5 fw-bold" onclick="return confirmDelete(this, 'Kalıcı Olarak Sil?', 'Bu fizibilite senaryosu veritabanından kalıcı olarak silinecek ve geri getirilemeyecektir!')">
                                                <i class="ri-delete-bin-fill me-1"></i>Kalıcı Sil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
