@extends('layouts.master')

@section('title', 'Sistem Uyum & Güvenlik Denetim Raporu')

@section('topbar-title', 'Aktivite Günlüğü - Uyum Raporu')

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
            <h1 class="fw-extrabold text-white mb-1">Sistem Uyum & Güvenlik Denetim Raporu</h1>
            <p class="text-white-50 mb-0 fs-6">ISO 27001 ve KVKK veri işleme güvenlik uyum özeti.</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="act-log-glass-card text-center mb-0">
            <small class="text-muted fw-bold text-uppercase d-block mb-1">Toplam İşlem</small>
            <h2 class="fw-extrabold text-primary mb-0">{{ number_format($summary['total_actions'] ?? 0) }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="act-log-glass-card text-center mb-0">
            <small class="text-muted fw-bold text-uppercase d-block mb-1">Farklı Kullanıcı</small>
            <h2 class="fw-extrabold text-success mb-0">{{ number_format($summary['unique_users'] ?? 0) }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="act-log-glass-card text-center mb-0">
            <small class="text-muted fw-bold text-uppercase d-block mb-1">İşlem Türü Sayısı</small>
            <h2 class="fw-extrabold text-info mb-0">{{ count($summary['actions_by_type'] ?? []) }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="act-log-glass-card text-center mb-0">
            <small class="text-muted fw-bold text-uppercase d-block mb-1">Etkilenen Modüller</small>
            <h2 class="fw-extrabold text-warning mb-0">{{ count($summary['models_affected'] ?? []) }}</h2>
        </div>
    </div>
</div>

<div class="act-log-glass-card">
    <h5 class="fw-bold text-dark mb-3">
        <i class="ri-file-list-3-line text-primary me-2"></i>Dönem İçi Güvenlik Günlükleri
    </h5>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Zaman</th>
                    <th>Kullanıcı</th>
                    <th>İşlem</th>
                    <th>Model</th>
                    <th>Açıklama</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $act)
                    <tr>
                        <td class="ps-4 text-muted small font-monospace">{{ \Carbon\Carbon::parse($act->created_at)->format('d.m.Y H:i:s') }}</td>
                        <td><strong class="text-dark">{{ $act->user->name ?? 'Sistem' }}</strong></td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary border rounded-pill px-3 py-1 text-uppercase">{{ $act->action }}</span></td>
                        <td><span class="font-monospace text-dark">{{ class_basename($act->model_type) }}</span></td>
                        <td class="text-muted small">{{ $act->description ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
