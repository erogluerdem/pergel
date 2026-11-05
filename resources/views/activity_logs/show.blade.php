@extends('layouts.master')

@section('title', 'Aktivite Günlüğü Detayı')

@section('topbar-title', 'Aktivite Günlüğü - Detay')

@push('styles')
<style>
    .act-log-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #3b82f6 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .act-log-glass-card {
        border-radius: 20px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .act-log-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .json-box {
        background: #0f172a;
        color: #38bdf8;
        border-radius: 12px;
        padding: 1.25rem;
        font-family: monospace;
        font-size: 0.875rem;
        max-height: 280px;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')

<div class="act-log-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Aktivite Günlüğü
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Aktivite İşlem Detayı</h1>
            <p class="text-white-50 mb-0 fs-6">İşlem zamanı: {{ \Carbon\Carbon::parse($activityLog->created_at)->format('d.m.Y H:i:s') }}</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="act-log-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-4">
                <i class="ri-user-line text-primary me-2"></i>İşlemi Yapan Kullanıcı & Metadata
            </h5>
            <div class="list-group list-group-flush">
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Kullanıcı Adı</span>
                    <strong class="text-dark">{{ $activityLog->user->name ?? 'Sistem' }}</strong>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">E-Posta</span>
                    <span class="font-monospace text-dark">{{ $activityLog->user->email ?? '-' }}</span>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">İşlem (Action)</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary border rounded-pill px-3 py-1 fw-bold text-uppercase">{{ $activityLog->action }}</span>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">IP Adresi</span>
                    <span class="font-monospace text-dark">{{ $activityLog->ip_address ?? '127.0.0.1' }}</span>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Tarayıcı (User Agent)</span>
                    <small class="text-muted text-truncate" style="max-width: 260px;">{{ $activityLog->user_agent ?? 'Unknown' }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="act-log-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-3">
                <i class="ri-code-s-slash-line text-info me-2"></i>Veri Değişiklikleri (Diff)
            </h5>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Önceki Değerler (Old Values)</label>
                <div class="json-box">
                    <pre class="mb-0">{{ json_encode($activityLog->old_values ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>

            <div>
                <label class="form-label small fw-bold text-muted">Yeni Değerler (New Values)</label>
                <div class="json-box">
                    <pre class="mb-0">{{ json_encode($activityLog->new_values ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
