@extends('layouts.master')

@section('title', 'Yeni Kullanıcı Hesabı Ekle')

@section('topbar-title', 'Kullanıcı Yönetimi - Yeni Kayıt')

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
        padding: 2rem;
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
</style>
@endpush

@section('content')

<div class="usr-adm-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Kullanıcı Listesi
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Kullanıcı Hesabı Tanımla</h1>
            <p class="text-white-50 mb-0 fs-6">Sisteme yeni personel veya yönetici yetkili kullanıcı hesabı açın.</p>
        </div>
    </div>
</div>

<div class="usr-adm-glass-card">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold text-dark">Ad Soyad <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ahmet Yılmaz" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold text-dark">E-Posta Adresi <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="ahmet@pergel.com" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold text-dark">Şifre <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold text-dark">Şifre Tekrarı <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="col-md-6 pt-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold text-dark" for="is_admin">
                        Yönetici Yetkisi Ver (Sistem Admin)
                    </label>
                </div>
            </div>

            <div class="col-md-6 pt-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="verified" id="verified" value="1" checked>
                    <label class="form-check-label fw-bold text-dark" for="verified">
                        E-Posta Adresini Otomatik Doğrula
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
            <button type="submit" class="btn btn-indigo text-white rounded-pill px-5 fw-bold" style="background:#4338ca;">
                <i class="ri-user-add-line me-1"></i>Kullanıcıyı Kaydet
            </button>
        </div>
    </form>
</div>

@endsection