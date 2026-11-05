@extends('layouts.master')

@section('title', 'Yeni KVKK Onay Talebi')

@push('styles')
<style>
    .kvkk-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .kvkk-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: kvkkCreateGlow 4s linear infinite;
    }

    @keyframes kvkkCreateGlow {
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
    <div class="kvkk-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill kvkk-badge-pill">
                        <i class="ri-shield-check-fill text-warning fs-5"></i> 6698 SAYILI KVKK UYUMLU
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Onay Talebi
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni KVKK & İletişim Onay Talebi</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    6698 sayılı kanun kapsamında müşteriden dijital SMS/E-posta onay talebi gönderin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('kvkk-consents.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Tüm Onay Listesi
                </a>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-kvkk-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-shield-keyhole-line text-warning fs-5"></i>Dijital Rıza & İzin Oluşturma Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('kvkk-consents.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="customer_id" class="form-label-custom">Müşteri Seçimi (Opsiyonel)</label>
                    <select name="customer_id" id="customer_id" class="form-select custom-input-kvkk">
                        <option value="">-- Mevcut Müşterilerden Seçiniz (Opsiyonel) --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" 
                                    data-phone="{{ $customer->phone }}" 
                                    data-email="{{ $customer->email }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                👤 {{ $customer->name }} ({{ $customer->phone ?? 'Telefon yok' }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted fw-bold d-block mt-1">Müşteri seçildiğinde telefon ve e-posta otomatik doldurulur.</small>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="phone" class="form-label-custom">Telefon Numarası (SMS OTP) <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" id="phone" class="form-control custom-input-kvkk font-monospace @error('phone') is-invalid @enderror" 
                               placeholder="05XX XXX XX XX" value="{{ old('phone') }}" required>
                        @error('phone')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label-custom">E-posta Adresi</label>
                        <input type="email" name="email" id="email" class="form-control custom-input-kvkk font-monospace @error('email') is-invalid @enderror" 
                               placeholder="ornek@domain.com" value="{{ old('email') }}">
                        @error('email')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="consent_type" class="form-label-custom">Onay Yöntemi & Türü <span class="text-danger">*</span></label>
                    <select name="consent_type" id="consent_type" class="form-select custom-input-kvkk @error('consent_type') is-invalid @enderror" required>
                        <option value="">-- Onay Yöntemini Seçiniz --</option>
                        <option value="sms" {{ old('consent_type', 'sms') === 'sms' ? 'selected' : '' }}>📱 SMS OTP ile Doğrulama (Tek Kullanımlık Kod)</option>
                        <option value="email" {{ old('consent_type') === 'email' ? 'selected' : '' }}>📧 E-posta Bağlantısı ile Doğrulama</option>
                        <option value="both" {{ old('consent_type') === 'both' ? 'selected' : '' }}>🛡️ SMS + E-posta (Çift Aşamalı Çapraz Doğrulama)</option>
                    </select>
                    @error('consent_type')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label-custom">Talebe İlişkin Notlar / Açıklama</label>
                    <textarea name="notes" id="notes" class="form-control custom-input-kvkk" rows="4" 
                              placeholder="Kullanıcı beyanı, görüşme kanalı (Web, Telefon, Yüz yüze)...">{{ old('notes') }}</textarea>
                </div>

                <div class="p-4 rounded-3 mb-4 text-dark" style="background: #fffbeb; border: 1.5px solid #fde68a;">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="ri-information-fill text-warning fs-4"></i>
                        <strong class="text-dark fs-6 fw-extrabold">KVKK Dijital Onay Çalışma Mantığı:</strong>
                    </div>
                    <ol class="mb-0 ps-3 fw-semibold text-dark" style="line-height: 1.8;">
                        <li>Talep oluşturulduğunda 6 haneli zaman damgalı SMS OTP doğrulama kodu üretilir.</li>
                        <li>Kod 24 saat boyunca geçerlidir ve maksimum 3 yanlış girme hakkı tanınır.</li>
                        <li>Kullanıcı doğrulamayı tamamladığında onay veritabanına kanıt niteliğinde kaydedilir.</li>
                    </ol>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('kvkk-consents.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-send-plane-fill me-1"></i>Onay Talebini Oluştur & Gönder
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerSelect = document.getElementById('customer_id');
        const phoneInput = document.getElementById('phone');
        const emailInput = document.getElementById('email');

        if(customerSelect) {
            customerSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (this.value) {
                    const phone = selectedOption.getAttribute('data-phone');
                    const email = selectedOption.getAttribute('data-email');
                    if(phone && phone !== 'null') phoneInput.value = phone;
                    if(email && email !== 'null') emailInput.value = email;
                }
            });
        }
    });
</script>
@endpush
