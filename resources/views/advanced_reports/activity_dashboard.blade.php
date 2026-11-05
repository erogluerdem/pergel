@extends('layouts.master')

@section('title', 'Sistem Aktivite Paneli')

@section('topbar-title', 'Gelişmiş Raporlar - Aktivite Günlüğü')

@push('styles')
<style>
    .adv-rep-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0284c7 50%, #0369a1 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .adv-rep-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .adv-rep-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="adv-rep-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('advanced_reports.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Raporlar Hub
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Sistem İşlem Günlükleri & Audit Trail</h1>
            <p class="text-white-50 mb-0 fs-6">Kullanıcı aktiviteleri, veri değişiklikleri ve sistem işlem izleri.</p>
        </div>
    </div>
</div>

<div class="adv-rep-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-history-line text-info me-2"></i>Son Aktivite Günlüğü
        </h5>
        <span class="badge bg-info bg-opacity-10 border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold text-info">
            Son {{ $days }} Gün
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Zaman</th>
                    <th>Kullanıcı</th>
                    <th>İşlem (Action)</th>
                    <th>Modül</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $act)
                    <tr>
                        <td class="ps-4 text-muted small">{{ \Carbon\Carbon::parse($act->created_at)->format('d.m.Y H:i') }}</td>
                        <td><strong class="text-dark">{{ $act->user->name ?? 'Sistem' }}</strong></td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary border rounded-pill px-3 py-1">{{ $act->action ?? 'LOG' }}</span></td>
                        <td>{{ $act->module ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Aktivite kaydı bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
