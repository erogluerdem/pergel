@extends('layouts.master')

@section('title', 'Kullanıcı Profil Detayı - ' . $user->name)

@section('topbar-title', 'Kullanıcı Yönetimi - Profil Detay')

@push('styles')
<style>
    .usr-adm-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #4338ca 50%, #3730a3 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .usr-adm-glass-card {
        border-radius: 20px;
        padding: 1.8rem;
        margin-bottom: 1.5rem;
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

    .user-large-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        font-weight: 800;
        color: #ffffff;
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
        box-shadow: 0 8px 24px rgba(67, 56, 202, 0.35);
    }
</style>
@endpush

@section('content')

<div class="usr-adm-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="user-large-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="fw-extrabold text-white mb-1">{{ $user->name }}</h1>
                <p class="text-white-50 mb-0 font-monospace">{{ $user->email }}</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="ri-arrow-left-line me-1"></i>Listeye Dön
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-light rounded-pill px-4 fw-bold text-indigo" style="color:#4338ca;">
                <i class="ri-edit-line me-1"></i>Kullanıcıyı Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="usr-adm-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-4">
                <i class="ri-user-line text-indigo me-2" style="color:#4338ca;"></i>Hesap Bilgileri
            </h5>
            <div class="list-group list-group-flush">
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Kullanıcı Adı</span>
                    <strong class="text-dark">{{ $user->name }}</strong>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">E-Posta Adresi</span>
                    <span class="font-monospace text-dark">{{ $user->email }}</span>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Sistem Yetki Rolü</span>
                    @if($user->is_admin)
                        <span class="badge bg-primary bg-opacity-10 text-primary border rounded-pill px-3 py-1 fw-bold">Admin</span>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-dark border rounded-pill px-3 py-1">Kullanıcı</span>
                    @endif
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Hesap Doğrulama</span>
                    @if($user->email_verified_at)
                        <span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1">Doğrulanmış</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border rounded-pill px-3 py-1">Doğrulanmamış</span>
                    @endif
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Kayıt Oluşturma Tarihi</span>
                    <strong class="text-dark">{{ \Carbon\Carbon::parse($user->created_at)->format('d.m.Y H:i') }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="usr-adm-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-4">
                <i class="ri-shield-keyhole-line text-success me-2"></i>Aktivite & Güvenlik Özeti
            </h5>
            <div class="list-group list-group-flush">
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Son Güncelleme Zamanı</span>
                    <strong class="text-dark">{{ \Carbon\Carbon::parse($user->updated_at)->format('d.m.Y H:i') }}</strong>
                </div>
                <div class="list-group-item bg-transparent d-flex justify-content-between py-3">
                    <span class="text-muted fw-bold">Kullanıcı ID Numarası</span>
                    <span class="font-monospace text-dark">#{{ $user->id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection