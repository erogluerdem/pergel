@extends('layouts.master')

@section('title', 'Kullanıcı Yönetimi & Sistem Yetkilendirme Portalı')

@section('topbar-title', 'Yönetim - Kullanıcılar')

@push('styles')
<style>
    /* Hero Header Banner */
    .usr-adm-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .usr-adm-hero-banner,
    :root:not([data-bs-theme="dark"]) .usr-adm-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #4338ca 50%, #3730a3 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .usr-adm-hero-banner {
        background: linear-gradient(135deg, rgba(67, 56, 202, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .usr-adm-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .usr-adm-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .usr-adm-card,
    :root:not([data-bs-theme="dark"]) .usr-adm-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .usr-adm-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .usr-adm-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Table Container */
    .usr-adm-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .usr-adm-glass-card,
    :root:not([data-bs-theme="dark"]) .usr-adm-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .usr-adm-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .user-avatar-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #ffffff;
        font-size: 1rem;
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
        box-shadow: 0 2px 8px rgba(67, 56, 202, 0.3);
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
<div class="usr-adm-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-shield-user-line me-1"></i>User & Role Administration
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Kullanıcı Yönetimi & Yetkilendirme</h1>
            <p class="text-white-50 mb-0 fs-6">Sistem kullanıcı hesapları, yetki tanımlamaları ve güvenlik doğrulamaları.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.export_csv') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold">
                <i class="ri-download-2-line me-1"></i>CSV İndir
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-light rounded-pill px-4 fw-bold text-indigo shadow-sm" style="color:#4338ca;">
                <i class="ri-user-add-line me-1"></i>Yeni Kullanıcı Ekle
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="usr-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Kullanıcı</span>
                    <h2 class="fw-extrabold mb-0 text-indigo" style="color:#4338ca;">{{ number_format($stats['total_users'] ?? 0) }} Kullanıcı</h2>
                </div>
                <div class="usr-adm-stat-icon bg-indigo bg-opacity-10 text-indigo" style="color:#4338ca;">
                    <i class="ri-group-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="usr-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Yönetici / Admin</span>
                    <h2 class="fw-extrabold mb-0 text-primary">{{ number_format($stats['admin_users'] ?? 0) }} Admin</h2>
                </div>
                <div class="usr-adm-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-admin-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="usr-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">E-Posta Doğrulanmış</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ number_format($stats['verified_users'] ?? 0) }} Hesap</h2>
                </div>
                <div class="usr-adm-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="usr-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Son 30 Gün Yeni Kayıt</span>
                    <h2 class="fw-extrabold mb-0 text-warning">{{ number_format($stats['recent_users'] ?? 0) }} Yeni</h2>
                </div>
                <div class="usr-adm-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-user-follow-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="usr-adm-glass-card p-3 mb-4">
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
        <div class="col-md-4">
            <label class="form-label small fw-bold text-dark mb-1">Arama Yap</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ri-search-line"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Ad, e-posta ile ara..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Rol Filtresi</label>
            <select name="admin_filter" class="form-select">
                <option value="">-- Tüm Rol Verileri --</option>
                <option value="admin" {{ request('admin_filter') === 'admin' ? 'selected' : '' }}>Yöneticiler (Admin)</option>
                <option value="user" {{ request('admin_filter') === 'user' ? 'selected' : '' }}>Standart Kullanıcılar</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Doğrulama Durumu</label>
            <select name="verified_filter" class="form-select">
                <option value="">-- Tüm Durumlar --</option>
                <option value="verified" {{ request('verified_filter') === 'verified' ? 'selected' : '' }}>Doğrulanmış Hesaplar</option>
                <option value="unverified" {{ request('verified_filter') === 'unverified' ? 'selected' : '' }}>Doğrulanmamış Hesaplar</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end pt-4">
            <button type="submit" class="btn btn-indigo text-white w-100 rounded-pill fw-bold" style="background:#4338ca;">
                <i class="ri-filter-3-line me-1"></i>Filtrele
            </button>
        </div>
    </form>
</div>

<!-- Main User Roster Table -->
<div class="usr-adm-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-user-shared-line text-indigo me-2" style="color:#4338ca;"></i>Kullanıcı Listesi & Hesap Yönetimi
        </h5>
        <span class="badge bg-indigo bg-opacity-10 border border-indigo border-opacity-30 rounded-pill px-3 py-1 fw-bold text-indigo" style="color:#4338ca;">
            Toplam {{ $users->total() }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Kullanıcı Profil</th>
                    <th>E-Posta Adresi</th>
                    <th>Yetki Rolü</th>
                    <th>E-Posta Doğrulama</th>
                    <th>Kayıt Tarihi</th>
                    <th class="text-end pe-4">Aksiyonlar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-avatar-circle">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $u->id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                        {{ $u->name }}
                                    </a>
                                    <small class="text-muted">ID: #{{ $u->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="font-monospace text-dark">{{ $u->email }}</span>
                        </td>
                        <td>
                            @if($u->is_admin)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                                    <i class="ri-shield-flash-line me-1"></i>Sistem Admin
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-dark border rounded-pill px-3 py-1">
                                    Kullanıcı
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($u->email_verified_at)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1">
                                    <i class="ri-checkbox-circle-fill me-1"></i>Doğrulanmış
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1">
                                    <i class="ri-time-line me-1"></i>Bekliyor
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ \Carbon\Carbon::parse($u->created_at)->format('d.m.Y H:i') }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.users.show', $u->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3 me-1" title="Detay">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 me-1" title="Düzenle">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="d-inline" onsubmit="return confirm('Bu kullanıcı hesabını silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Sil">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="ri-user-search-line fs-1 d-block mb-2 text-secondary"></i>
                            Arama kriterlerine uygun kullanıcı kaydı bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="d-flex justify-content-end mt-4">
        {{ $users->links() }}
    </div>
</div>

@endsection
