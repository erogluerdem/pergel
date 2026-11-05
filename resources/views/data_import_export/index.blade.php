@extends('layouts.master')

@section('title', 'Veri İçe & Dışa Aktarma Portalı')

@section('topbar-title', 'Yönetim - Veri İçe/Dışa Aktar')

@push('styles')
<style>
    /* Hero Header Banner */
    .die-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .die-hero-banner,
    :root:not([data-bs-theme="dark"]) .die-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .die-hero-banner {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .die-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .die-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .die-card,
    :root:not([data-bs-theme="dark"]) .die-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .die-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .die-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .die-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .die-glass-card,
    :root:not([data-bs-theme="dark"]) .die-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .die-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Flash Notifications -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
        <i class="ri-checkbox-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-pill px-4" role="alert">
        <i class="ri-error-warning-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Hero Header Banner -->
<div class="die-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-exchange-box-line me-1"></i>Data Import & Export Center
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Veri İçe & Dışa Aktarma Portalı</h1>
            <p class="text-white-50 mb-0 fs-6">Excel, CSV, JSON toplu veri yükleme, şantiye verilerini dışa aktarma ve işlem geçmişi.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('data-import-export.import_form') }}" class="btn btn-light rounded-pill px-4 fw-bold text-primary shadow-sm">
                <i class="ri-upload-cloud-2-line me-1"></i>Veri İçe Aktar (Import)
            </a>
            <a href="{{ route('data-import-export.export_form') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold">
                <i class="ri-download-cloud-2-line me-1"></i>Veri Dışa Aktar (Export)
            </a>
            <a href="{{ route('data-import-export.logs') }}" class="btn btn-outline-light rounded-pill px-3">
                <i class="ri-history-line"></i> Loglar
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="die-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam İşlem</span>
                    <h2 class="fw-extrabold mb-0 text-primary">{{ number_format($stats['total'] ?? 0) }} İşlem</h2>
                </div>
                <div class="die-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-exchange-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="die-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Başarıyla Tamamlanan</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ number_format($stats['completed'] ?? 0) }} İşlem</h2>
                </div>
                <div class="die-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="die-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Kuyrukta / İşlemde</span>
                    <h2 class="fw-extrabold mb-0 text-warning">{{ number_format($stats['processing'] ?? 0) }} İşlem</h2>
                </div>
                <div class="die-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-loader-4-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="die-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Hatalı / Başarısız</span>
                    <h2 class="fw-extrabold mb-0 text-danger">{{ number_format($stats['failed'] ?? 0) }} İşlem</h2>
                </div>
                <div class="die-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-error-warning-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import History Table -->
<div class="die-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-history-line text-primary me-2"></i>Son İçe Aktarım İşlem Geçmişi
        </h5>
        <span class="badge bg-primary bg-opacity-10 border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold text-primary">
            Toplam {{ $imports->total() }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Dosya Adı</th>
                    <th>Hedef Modül</th>
                    <th>Satır Sayısı</th>
                    <th>İşlenen / Hatalı</th>
                    <th>Durum</th>
                    <th>Tarih</th>
                    <th class="text-end pe-4">Aksiyonlar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($imports as $imp)
                    <tr>
                        <td class="ps-4">
                            <span class="font-monospace fw-bold text-dark d-block">{{ $imp->file_name }}</span>
                            <small class="text-muted">{{ $imp->file_path }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-dark border rounded-pill px-3 py-1 text-uppercase">
                                {{ $imp->module }}
                            </span>
                        </td>
                        <td><strong>{{ number_format($imp->total_rows ?? 0) }} Satır</strong></td>
                        <td>
                            <span class="text-success fw-bold">{{ number_format($imp->imported_rows ?? 0) }}</span> /
                            <span class="text-danger fw-bold">{{ number_format($imp->error_rows ?? 0) }}</span>
                        </td>
                        <td>
                            @if($imp->status === 'completed')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1">
                                    <i class="ri-checkbox-circle-fill me-1"></i>Tamamlandı
                                </span>
                            @elseif($imp->status === 'failed')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1">
                                    <i class="ri-error-warning-fill me-1"></i>Başarısız
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1">
                                    <i class="ri-time-line me-1"></i>İşlemde
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ \Carbon\Carbon::parse($imp->created_at)->format('d.m.Y H:i') }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('data-import-export.show', $imp->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3 me-1" title="Detay">
                                    <i class="ri-eye-line"></i>
                                </a>
                                @if(($imp->error_rows ?? 0) > 0)
                                    <a href="{{ route('data-import-export.download_errors', $imp->id) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 me-1" title="Hata Raporu İndir">
                                        <i class="ri-download-2-line"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ri-upload-cloud-line fs-1 d-block mb-2 text-secondary"></i>
                            Henüz içe aktarım işlemi gerçekleştirilmedi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="d-flex justify-content-end mt-4">
        {{ $imports->links() }}
    </div>
</div>

@endsection
