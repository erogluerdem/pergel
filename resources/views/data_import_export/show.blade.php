@extends('layouts.master')

@section('title', 'İçeri Aktarım İşlem Detayı')

@section('topbar-title', 'Veri Transferi - İşlem Detayı')

@push('styles')
<style>
    .die-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .die-glass-card {
        border-radius: 20px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
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

<div class="die-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('data-import-export.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Veri Transfer Merkezi
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">İşlem Detayı: {{ $import->file_name }}</h1>
            <p class="text-white-50 mb-0 fs-6">Aktarılan veri satırları, başarı durumu ve hata kayıtları.</p>
        </div>
        <div class="d-flex gap-2">
            @if(($import->error_rows ?? 0) > 0)
                <a href="{{ route('data-import-export.download_errors', $import->id) }}" class="btn btn-light rounded-pill px-4 fw-bold text-danger">
                    <i class="ri-download-2-line me-1"></i>Hata Raporu İndir
                </a>
            @endif
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="die-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-4">
                <i class="ri-file-info-line text-primary me-2"></i>Aktarım Özeti
            </h5>
            <div class="list-group list-group-flush">
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Dosya Adı</span>
                    <span class="font-monospace fw-bold text-dark">{{ $import->file_name }}</span>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Hedef Modül</span>
                    <span class="badge bg-secondary bg-opacity-10 text-dark border rounded-pill px-3 py-1 text-uppercase">{{ $import->module }}</span>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">İşlem Durumu</span>
                    <span class="badge bg-{{ $import->status === 'completed' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $import->status === 'completed' ? 'success' : 'warning' }} border rounded-pill px-3 py-1 fw-bold">
                        {{ ucfirst($import->status) }}
                    </span>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Toplam Satır</span>
                    <strong class="text-dark">{{ number_format($import->total_rows) }} Satır</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="die-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-4">
                <i class="ri-bar-chart-box-line text-success me-2"></i>Satır İlerleme Skor Kartı
            </h5>
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold text-dark">Aktarım Başarı Oranı</span>
                    <strong class="text-success">%{{ $import->getProgressPercentage() }}</strong>
                </div>
                <div class="progress" style="height: 12px; border-radius: 8px;">
                    <div class="progress-bar bg-success" style="width: {{ $import->getProgressPercentage() }}%;"></div>
                </div>
            </div>
            <div class="row text-center g-3 pt-2">
                <div class="col-6">
                    <div class="p-3 bg-success bg-opacity-10 rounded-3 border border-success border-opacity-30">
                        <small class="text-success d-block fw-bold mb-1">BAŞARIYLA EKLENEN</small>
                        <h3 class="fw-extrabold text-success mb-0">{{ number_format($import->imported_rows) }}</h3>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-danger bg-opacity-10 rounded-3 border border-danger border-opacity-30">
                        <small class="text-danger d-block fw-bold mb-1">HATALI / ATLANA SATIR</small>
                        <h3 class="fw-extrabold text-danger mb-0">{{ number_format($import->error_rows ?? 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
