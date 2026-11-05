@extends('layouts.master')

@section('title', 'KVKK Onay Talebi Düzenle — ' . ($kvkkConsent->customer?->name ?? $kvkkConsent->phone))

@push('styles')
<style>
    .kvkk-edit-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .kvkk-edit-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: kvkkEditGlow 4s linear infinite;
    }

    @keyframes kvkkEditGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .kvkk-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kvkk-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-kvkk {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-kvkk:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .form-label-custom {
        font-weight: 800 !important;
        color: #0f172a !important;
        font-size: 0.88rem !important;
        margin-bottom: 6px !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="kvkk-edit-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill kvkk-badge-pill">
                        <i class="ri-shield-check-fill text-warning fs-5"></i> 6698 SAYILI KVKK UYUMLU
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Rıza Kaydı Revizyonu
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">KVKK Onay Kaydını Düzenle — {{ $kvkkConsent->customer?->name ?? $kvkkConsent->phone }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    İletişim bilgilerini, rıza durumunu ve sistem notlarını güncelleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('kvkk-consents.show', $kvkkConsent->id) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-eye-line me-1"></i>Detaylara Dön
                    </a>
                    <a href="{{ route('kvkk-consents.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Tüm Onaylar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-kvkk-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-edit-fill text-warning fs-5"></i>KVKK & Açık Rıza Düzenleme Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('kvkk-consents.update', $kvkkConsent->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="customer_id" class="form-label-custom">İlişkili Müşteri (Opsiyonel)</label>
                    <select name="customer_id" id="customer_id" class="form-select custom-input-kvkk">
                        <option value="">-- Bağımsız Müşteri (Seçim Yok) --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" 
                                    data-phone="{{ $customer->phone }}" 
                                    data-email="{{ $customer->email }}"
                                    {{ old('customer_id', $kvkkConsent->customer_id) == $customer->id ? 'selected' : '' }}>
                                👤 {{ $customer->name }} ({{ $customer->phone ?? 'Telefon yok' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="phone" class="form-label-custom">Telefon Numarası <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" id="phone" class="form-control custom-input-kvkk font-monospace @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $kvkkConsent->phone) }}" required>
                        @error('phone')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label-custom">E-posta Adresi</label>
                        <input type="email" name="email" id="email" class="form-control custom-input-kvkk font-monospace @error('email') is-invalid @enderror" 
                               value="{{ old('email', $kvkkConsent->email) }}">
                        @error('email')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="consent_type" class="form-label-custom">Onay Yöntemi <span class="text-danger">*</span></label>
                        <select name="consent_type" id="consent_type" class="form-select custom-input-kvkk @error('consent_type') is-invalid @enderror" required>
                            <option value="sms" {{ old('consent_type', $kvkkConsent->consent_type) === 'sms' ? 'selected' : '' }}>📱 SMS OTP ile Doğrulama</option>
                            <option value="email" {{ old('consent_type', $kvkkConsent->consent_type) === 'email' ? 'selected' : '' }}>📧 E-posta Bağlantısı</option>
                            <option value="both" {{ old('consent_type', $kvkkConsent->consent_type) === 'both' ? 'selected' : '' }}>🛡️ SMS + E-posta (Çift Aşamalı)</option>
                        </select>
                        @error('consent_type')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="consent_status" class="form-label-custom">Rıza Durumu <span class="text-danger">*</span></label>
                        <select name="consent_status" id="consent_status" class="form-select custom-input-kvkk @error('consent_status') is-invalid @enderror" required>
                            <option value="pending" {{ old('consent_status', $kvkkConsent->consent_status) === 'pending' ? 'selected' : '' }}>⏳ Doğrulama Bekliyor (Pending)</option>
                            <option value="verified" {{ old('consent_status', $kvkkConsent->consent_status) === 'verified' ? 'selected' : '' }}>✅ Doğrulandı (Verified)</option>
                            <option value="rejected" {{ old('consent_status', $kvkkConsent->consent_status) === 'rejected' ? 'selected' : '' }}>❌ Reddedildi (Rejected)</option>
                            <option value="expired" {{ old('consent_status', $kvkkConsent->consent_status) === 'expired' ? 'selected' : '' }}>⏰ Süresi Doldu (Expired)</option>
                        </select>
                        @error('consent_status')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label-custom">Notlar & Açıklama</label>
                    <textarea name="notes" id="notes" class="form-control custom-input-kvkk" rows="4">{{ old('notes', $kvkkConsent->notes) }}</textarea>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('kvkk-consents.show', $kvkkConsent->id) }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Değişiklikleri Kaydet & Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
