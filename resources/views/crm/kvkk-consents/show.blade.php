@extends('layouts.master')

@section('title', 'KVKK Onay Detayı — ' . ($kvkkConsent->customer?->name ?? $kvkkConsent->phone))

@push('styles')
<style>
    .kvkk-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .kvkk-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: kvkkShowGlow 4s linear infinite;
    }

    @keyframes kvkkShowGlow {
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

    .custom-kpi-card-kvkkshow {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-kvkkshow:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-show-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .otp-code-display {
        font-family: monospace;
        font-size: 1.8rem;
        letter-spacing: 0.4rem;
        background: #0f172a;
        color: #fef08a;
        border: 2px dashed #d97706;
        padding: 0.6rem 1.25rem;
        border-radius: 12px;
        display: inline-block;
        font-weight: 800;
    }

    .otp-input-field {
        font-size: 2.2rem;
        text-align: center;
        letter-spacing: 0.8rem;
        font-weight: 800;
        border-radius: 14px;
        border: 2px solid #d97706 !important;
        background-color: #f8fafc !important;
        color: #0f172a !important;
    }
    .otp-input-field:focus {
        background-color: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.2) !important;
    }

    /* Timeline Styling */
    .kvkk-timeline-step {
        display: flex;
        gap: 1.25rem;
        position: relative;
        padding-bottom: 1.75rem;
    }

    .kvkk-timeline-step::before {
        content: '';
        position: absolute;
        left: 1.25rem;
        top: 2.5rem;
        bottom: 0;
        width: 2px;
        background: #cbd5e1;
    }

    .kvkk-timeline-step:last-child::before {
        display: none;
    }

    .kvkk-circle {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="kvkk-show-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    @if($kvkkConsent->consent_status === 'verified')
                        <span class="badge bg-success text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #16a34a !important;">✓ RİZA DOĞRULANDI</span>
                    @elseif($kvkkConsent->consent_status === 'pending')
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 font-monospace fw-bold">⏳ DOĞRULAMA BEKLİYOR</span>
                    @elseif($kvkkConsent->consent_status === 'rejected')
                        <span class="badge bg-danger text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #ef4444 !important;">❌ REDDEDİLDİ</span>
                    @else
                        <span class="badge bg-secondary text-white rounded-pill px-3 py-2 font-monospace fw-bold">⏰ ZAMAN AŞIMI</span>
                    @endif

                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill kvkk-badge-pill font-monospace">
                        <i class="ri-phone-fill text-warning fs-5"></i> {{ $kvkkConsent->phone }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">👤 {{ $kvkkConsent->customer?->name ?? 'Bağımsız Müşteri Kaydı' }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Oluşturulma: <strong class="text-white fw-bold">{{ $kvkkConsent->created_at->format('d.m.Y H:i') }}</strong> | 
                    Geçerlilik: <strong class="text-white fw-bold">{{ $kvkkConsent->expiry_date ? $kvkkConsent->expiry_date->format('d.m.Y H:i') : '-' }}</strong>
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('kvkk-consents.edit', $kvkkConsent->id) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-edit-fill me-1"></i>Düzenle
                    </a>
                    <a href="{{ route('kvkk-consents.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Tüm Onaylar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUCCESS ALERT -->
    @if($kvkkConsent->isVerified())
        <div class="alert alert-success rounded-4 p-3.5 mb-4 d-flex align-items-center gap-3 bg-success text-white border-0 shadow-sm" role="alert" style="background: #16a34a !important;">
            <i class="ri-checkbox-circle-fill fs-1 text-white"></i>
            <div>
                <h5 class="fw-extrabold text-white mb-1">6698 Sayılı KVKK ve Ticari İleti Onayı Başarıyla Alınmıştır</h5>
                <p class="mb-0 fs-6 fw-semibold text-white-50">Müşteri SMS OTP doğrulama adımlarını tamamlamıştır. Pazarlama ve bilgilendirme iletileri güvenle gönderilebilir.</p>
            </div>
        </div>
    @endif

    <!-- SPEC KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-kvkkshow h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">MÜŞTERİ İLETİŞİM</small>
                        <div class="fw-extrabold text-primary text-truncate" style="font-size: 1.3rem;">
                            {{ $kvkkConsent->customer?->name ?? $kvkkConsent->phone }}
                        </div>
                        <small class="text-primary fw-bold"><i class="ri-user-3-fill me-1"></i>Kayıtlı İletişim Profili</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-user-3-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-kvkkshow h-100 border-start border-warning border-4 text-center">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-start">
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">DOĞRULAMA OTP KODU</small>
                        <div class="mt-1">
                            <span class="otp-code-display">{{ $kvkkConsent->sms_code ?? '------' }}</span>
                        </div>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-key-2-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-kvkkshow h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">ONAY TÜRÜ</small>
                        <div class="fw-extrabold text-info font-monospace text-uppercase" style="font-size: 1.4rem;">
                            {{ $kvkkConsent->consent_type === 'both' ? 'SMS + E-POSTA' : strtoupper($kvkkConsent->consent_type) }}
                        </div>
                        <small class="text-info fw-bold"><i class="ri-shake-hands-fill me-1"></i>Doğrulama Metodu</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-shield-check-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="row">
        <!-- LEFT COLUMN: DETAILS & VERIFICATION ACTION -->
        <div class="col-lg-7">
            <!-- INFO CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-information-line text-warning fs-5"></i>Rıza Metni & İletişim Bilgileri
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4 mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Telefon Numarası</small>
                            <strong class="fs-5 text-dark fw-extrabold"><i class="ri-phone-fill me-1 text-primary"></i>{{ $kvkkConsent->phone }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">E-posta Adresi</small>
                            <strong class="fs-5 text-dark fw-extrabold"><i class="ri-mail-fill me-1 text-info"></i>{{ $kvkkConsent->email ?? 'Belirtilmedi' }}</strong>
                        </div>
                    </div>

                    @if($kvkkConsent->notes)
                        <div class="p-3.5 rounded-3 text-dark fw-semibold mt-3" style="background: #f8fafc; border: 1px solid #cbd5e1; line-height: 1.8;">
                            <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 11px;"><i class="ri-draft-fill text-warning me-1"></i>Sistem Notu / Açıklama:</small>
                            <span class="fs-6 text-dark font-sans">{{ $kvkkConsent->notes }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SMS OTP VERIFICATION ACTION BOX -->
            @if(!$kvkkConsent->sms_verified_at && $kvkkConsent->isPending())
                <div class="card custom-show-card border-warning mb-4">
                    <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                        <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                            <i class="ri-shield-keyhole-line text-warning fs-5"></i>SMS Doğrulama Kodunu Giriniz
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger rounded-4 p-3 mb-3 bg-danger text-white border-0" role="alert" style="background: #ef4444 !important;">
                                <i class="ri-error-warning-fill me-2"></i>{{ $message }}
                            </div>
                        @endif

                        <form action="{{ route('kvkk-consents.verify-sms', $kvkkConsent->id) }}" method="POST">
                            @csrf
                            
                            <div class="mb-4 text-center">
                                <label for="code" class="form-label fw-extrabold text-dark mb-2 fs-6">6 Haneli OTP Kodunu Giriniz</label>
                                <input type="text" name="code" id="code" class="form-control otp-input-field mx-auto @error('code') is-invalid @enderror" 
                                       placeholder="000000" maxlength="6" pattern="[0-9]{6}" style="max-width: 320px;" required>
                                @error('code')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                                <small class="text-muted d-block mt-2 fw-semibold">
                                    <i class="ri-cellphone-fill me-1 text-primary"></i>Kod <strong>{{ $kvkkConsent->phone }}</strong> numarasına gönderilmiştir.
                                </small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center p-3.5 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #cbd5e1;">
                                <small class="text-muted fw-bold">Kalan Deneme Hakkı:</small>
                                <span class="badge bg-warning text-dark font-monospace fs-6 px-3 py-1 rounded-pill fw-extrabold">{{ $kvkkConsent->verification_attempts }}/3 Deneme</span>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                                    <i class="ri-check-double-fill me-1"></i>SMS Kodunu Doğrula
                                </button>
                            </div>
                        </form>

                        <div class="border-top pt-3 mt-3">
                            <form method="POST" action="{{ route('kvkk-consents.reject', $kvkkConsent->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold" onclick="return confirm('Bu rıza talebini reddetmek istediğinize emin misiniz?')">
                                    <i class="ri-close-circle-fill me-1"></i>Rıza Talebini Reddet
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- EMAIL VERIFICATION LINK SENDER -->
            @if($kvkkConsent->email && $kvkkConsent->consent_type === 'both' && !$kvkkConsent->email_verified_at)
                <div class="card custom-show-card border-info mb-4">
                    <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                        <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                            <i class="ri-mail-send-line text-info fs-5"></i>E-posta Doğrulama Bağlantısı
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <p class="text-dark fw-semibold mb-3">
                            <strong>{{ $kvkkConsent->email }}</strong> adresine dijital KVKK onay linkini tekrar e-posta ile gönderebilirsiniz.
                        </p>
                        <form method="POST" action="{{ route('kvkk-consents.send-email', $kvkkConsent->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm">
                                <i class="ri-send-plane-fill me-1"></i>Doğrulama E-postası Gönder
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- RIGHT COLUMN: TIMELINE STEPPER & DANGEROUS ACTIONS -->
        <div class="col-lg-5">
            <!-- TIMELINE STEPPER -->
            <div class="card custom-show-card mb-4">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-history-line text-warning fs-5"></i>Doğrulama Adımları
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="ps-2">
                        <!-- SMS Step -->
                        <div class="kvkk-timeline-step">
                            <div class="kvkk-circle {{ $kvkkConsent->sms_verified_at ? 'bg-success text-white' : 'bg-secondary bg-opacity-20 text-muted' }}" style="{{ $kvkkConsent->sms_verified_at ? 'background: #16a34a !important;' : '' }}">
                                <i class="{{ $kvkkConsent->sms_verified_at ? 'ri-check-line' : 'ri-cellphone-line' }}"></i>
                            </div>
                            <div>
                                <h6 class="fw-extrabold text-dark mb-1">SMS OTP Doğrulaması</h6>
                                @if($kvkkConsent->sms_verified_at)
                                    <small class="text-success fw-bold d-block font-monospace"><i class="ri-checkbox-circle-fill me-1"></i>{{ $kvkkConsent->sms_verified_at->format('d.m.Y H:i') }}</small>
                                @else
                                    <small class="text-muted fw-bold d-block"><i class="ri-time-line me-1"></i>SMS Kod Doğrulaması Bekleniyor</small>
                                @endif
                            </div>
                        </div>

                        <!-- Email Step -->
                        <div class="kvkk-timeline-step">
                            <div class="kvkk-circle {{ $kvkkConsent->email_verified_at ? 'bg-success text-white' : 'bg-secondary bg-opacity-20 text-muted' }}" style="{{ $kvkkConsent->email_verified_at ? 'background: #16a34a !important;' : '' }}">
                                <i class="{{ $kvkkConsent->email_verified_at ? 'ri-check-line' : 'ri-mail-line' }}"></i>
                            </div>
                            <div>
                                <h6 class="fw-extrabold text-dark mb-1">E-posta Doğrulaması</h6>
                                @if($kvkkConsent->email_verified_at)
                                    <small class="text-success fw-bold d-block font-monospace"><i class="ri-checkbox-circle-fill me-1"></i>{{ $kvkkConsent->email_verified_at->format('d.m.Y H:i') }}</small>
                                @else
                                    <small class="text-muted fw-bold d-block"><i class="ri-time-line me-1"></i>E-posta Onayı Bekleniyor</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DANGEROUS ACTIONS -->
            <div class="card custom-show-card">
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('kvkk-consents.edit', $kvkkConsent->id) }}" class="btn btn-warning text-dark rounded-pill py-2.5 fw-extrabold shadow-sm">
                            <i class="ri-edit-fill me-1"></i>Kaydı Düzenle
                        </a>
                        <form action="{{ route('kvkk-consents.destroy', $kvkkConsent->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill py-2.5 fw-extrabold" onclick="return confirmDelete(this, 'KVKK Kaydını Sil?', 'Bu rıza/onay kaydı tamamen silinecektir.')">
                                <i class="ri-delete-bin-line me-1"></i>KVKK Kaydını Sil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
