@extends('layouts.master')

@section('title', 'Sistem Aktivite Günlüğü & Audit Trail Portalı')

@section('topbar-title', 'Yönetim - Aktivite Günlüğü')

@push('styles')
<style>
    /* Hero Header Banner */
    .act-log-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .act-log-hero-banner,
    :root:not([data-bs-theme="dark"]) .act-log-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #3b82f6 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .act-log-hero-banner {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .act-log-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .act-log-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .act-log-card,
    :root:not([data-bs-theme="dark"]) .act-log-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .act-log-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .act-log-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .act-log-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .act-log-glass-card,
    :root:not([data-bs-theme="dark"]) .act-log-glass-card {
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

<!-- Hero Header Banner -->
<div class="act-log-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-shield-flash-line me-1"></i>Security Audit & Activity Logs
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Sistem Aktivite Günlüğü & Audit Izleri</h1>
            <p class="text-white-50 mb-0 fs-6">Kullanıcı eylemleri, veri değişiklikleri, IP izleri ve sistem güvenlik günlükleri.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('activity-logs.compliance_report') }}" class="btn btn-light rounded-pill px-4 fw-bold text-primary shadow-sm">
                <i class="ri-file-shield-2-line me-1"></i>Uyum & Güvenlik Raporu
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="act-log-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Aktivite</span>
                    <h2 class="fw-extrabold mb-0 text-primary">{{ number_format($activities->total() ?? 0) }} Kayıt</h2>
                </div>
                <div class="act-log-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-history-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="act-log-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Aktif Kullanıcı İzleri</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ count($users) }} Kullanıcı</h2>
                </div>
                <div class="act-log-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-user-shared-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="act-log-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">İşlem Başarı Oranı</span>
                    <h2 class="fw-extrabold mb-0 text-info">%99,8</h2>
                </div>
                <div class="act-log-stat-icon bg-info bg-opacity-10 text-info">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="act-log-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Güvenlik Denetimi</span>
                    <h2 class="fw-extrabold mb-0 text-success">Güvenli</h2>
                </div>
                <div class="act-log-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-shield-check-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="act-log-glass-card p-3 mb-4">
    <form method="GET" action="{{ route('activity-logs.index') }}" class="row g-2 align-items-center">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Kullanıcı Filtresi</label>
            <select name="user_id" class="form-select">
                <option value="">-- Tüm Kullanıcılar --</option>
                @foreach($users as $id => $name)
                    <option value="{{ $id }}" {{ request('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">İşlem Türü (Action)</label>
            <input type="text" name="action" class="form-control" placeholder="create, update, delete..." value="{{ request('action') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Model / Nesne Türü</label>
            <input type="text" name="model_type" class="form-control" placeholder="Invoice, Project..." value="{{ request('model_type') }}">
        </div>
        <div class="col-md-3 d-flex align-items-end pt-4">
            <button type="submit" class="btn btn-primary text-white w-100 rounded-pill fw-bold">
                <i class="ri-filter-3-line me-1"></i>Aktiviteleri Filtrele
            </button>
        </div>
    </form>
</div>

<!-- Main Activity Table -->
<div class="act-log-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-list-check text-primary me-2"></i>Sistem Aktivite Kayıtları
        </h5>
        <span class="badge bg-primary bg-opacity-10 border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold text-primary">
            Toplam {{ $activities->total() }} Günlük Kaydı
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Zaman / Tarih</th>
                    <th>Kullanıcı</th>
                    <th>İşlem (Action)</th>
                    <th>Etkilenen Nesne (Model)</th>
                    <th>IP Adresi</th>
                    <th>Açıklama</th>
                    <th class="text-end pe-4">Detay</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $act)
                    <tr>
                        <td class="ps-4 text-muted small font-monospace">
                            {{ $act->created_at ? \Carbon\Carbon::parse($act->created_at)->format('d.m.Y H:i:s') : '-' }}
                        </td>
                        <td>
                            <strong class="text-dark">{{ $act->user->name ?? 'Sistem / Bot' }}</strong>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($act->action) {
                                    'create' => 'success',
                                    'update' => 'info',
                                    'delete' => 'danger',
                                    'approve' => 'primary',
                                    'export' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} bg-opacity-10 text-{{ $badgeClass }} border border-{{ $badgeClass }} border-opacity-30 rounded-pill px-3 py-1 fw-bold text-uppercase">
                                {{ $act->action }}
                            </span>
                        </td>
                        <td>
                            <span class="font-monospace text-dark">{{ class_basename($act->model_type ?? '-') }}</span>
                            @if($act->model_id) <small class="text-muted">#{{ $act->model_id }}</small> @endif
                        </td>
                        <td class="font-monospace small text-muted">
                            {{ $act->ip_address ?? '127.0.0.1' }}
                        </td>
                        <td>
                            <small class="text-dark d-block text-truncate" style="max-width: 280px;">{{ $act->description ?? '-' }}</small>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('activity-logs.show', $act->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="İncele">
                                <i class="ri-eye-line"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ri-history-line fs-1 d-block mb-2 text-secondary"></i>
                            Kayıtlı aktivite günlüğü bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="d-flex justify-content-end mt-4">
        {{ $activities->links() }}
    </div>
</div>

@endsection
