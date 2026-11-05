@extends('layouts.master')

@section('title', 'KVKK & Dijital Rıza Yönetimi')

@push('styles')
<style>
    .kvkk-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .kvkk-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: kvkkGlow 4s linear infinite;
    }

    @keyframes kvkkGlow {
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

    .custom-kpi-card-kvkk {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-kvkk:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-card-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
    }

    .custom-kvkk-table thead,
    .custom-kvkk-table thead tr,
    .custom-kvkk-table thead th {
        background-color: #0f172a !important;
        color: #ffffff !important;
        padding: 16px !important;
        font-weight: 800 !important;
        font-size: 0.85rem !important;
        border-bottom: 2px solid #d97706 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        --bs-table-bg: #0f172a !important;
        --bs-table-color: #ffffff !important;
    }

    .custom-kvkk-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 600 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="kvkk-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill kvkk-badge-pill">
                        <i class="ri-shield-check-fill text-warning fs-5"></i> 6698 SAYILI KVKK UYUMLU
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        Çift Aşamalı Doğrulama (OTP + E-posta)
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">KVKK ve İletişim Onayları</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Müşteri açık rıza beyanları, SMS OTP doğrulamaları ve dijital izin arşiv yönetimi.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('kvkk-consents.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="ri-add-circle-fill fs-5"></i>+ Yeni Onay Talebi
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ $message }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-danger text-white" role="alert" style="background: #ef4444 !important;">
            <i class="ri-error-warning-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ $message }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kvkk h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM RIZA KAYDI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $consents->total() }} Kayıt</div>
                        <small class="text-primary fw-bold"><i class="ri-shield-user-fill me-1"></i>Tüm Rıza İzinleri</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-shield-user-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kvkk h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">DOĞRULANMIŞ ONAYLAR</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">
                            {{ $consents->where('consent_status', 'verified')->count() }} Kayıt
                        </div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>OTP / Link Onaylı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kvkk h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">BEKLEYEN DOĞRULAMALAR</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">
                            {{ $consents->where('consent_status', 'pending')->count() }} Kayıt
                        </div>
                        <small class="text-warning fw-bold"><i class="ri-time-fill me-1"></i>Kullanıcı Onayı Bekleyen</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-time-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-kvkk h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">RED / ZAMAN AŞIMI</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.8rem;">
                            {{ $consents->whereIn('consent_status', ['rejected', 'expired'])->count() }} Kayıt
                        </div>
                        <small class="text-danger fw-bold"><i class="ri-close-circle-fill me-1"></i>Reddedilmiş / İptal</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fef2f2 !important; border: 1px solid #fecaca !important;">
                        <i class="ri-close-circle-fill fs-2" style="color: #dc2626 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-shield-keyhole-line text-warning fs-5"></i>KVKK & Açık Rıza Kayıt Cetveli
            </h5>
            <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                {{ $consents->total() }} Rıza Kaydı
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-kvkk-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Müşteri / İletişim Bilgisi</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Onay Türü</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Genel Durum</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">SMS OTP Doğrulama</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">E-posta Doğrulama</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Tarih</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consents as $consent)
                            <tr>
                                <td class="ps-4 py-3">
                                    <strong class="text-dark d-block fs-6 fw-extrabold">
                                        👤 {{ $consent->customer?->name ?? 'Bağımsız Müşteri' }}
                                    </strong>
                                    <small class="text-muted fw-bold d-block"><i class="ri-phone-line me-1"></i>{{ $consent->phone }}</small>
                                    @if($consent->email)
                                        <small class="text-muted fw-bold d-block"><i class="ri-mail-line me-1"></i>{{ $consent->email }}</small>
                                    @endif
                                </td>
                                <td class="font-monospace">
                                    @if($consent->consent_type === 'sms')
                                        <span class="badge bg-info text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">📱 SMS OTP</span>
                                    @elseif($consent->consent_type === 'email')
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">✉️ E-posta Link</span>
                                    @else
                                        <span class="badge bg-purple text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">🤝 SMS + E-posta</span>
                                    @endif
                                </td>
                                <td class="text-center font-monospace">
                                    @if($consent->consent_status === 'verified')
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">✓ DOĞRULANDI</span>
                                    @elseif($consent->consent_status === 'pending')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">⏳ BEKLİYOR</span>
                                    @elseif($consent->consent_status === 'rejected')
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #ef4444 !important;">❌ REDDEDİLDİ</span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">⏰ SÜRESİ DOLDU</span>
                                    @endif
                                </td>
                                <td class="font-monospace">
                                    @if($consent->sms_verified_at)
                                        <span class="text-success fw-bold font-monospace"><i class="ri-checkbox-circle-fill me-1"></i>{{ $consent->sms_verified_at->format('d.m.Y H:i') }}</span>
                                    @else
                                        <span class="text-muted small"><i class="ri-time-line me-1"></i>Beklemede</span>
                                    @endif
                                </td>
                                <td class="font-monospace">
                                    @if($consent->email_verified_at)
                                        <span class="text-success fw-bold font-monospace"><i class="ri-checkbox-circle-fill me-1"></i>{{ $consent->email_verified_at->format('d.m.Y H:i') }}</span>
                                    @else
                                        <span class="text-muted small"><i class="ri-time-line me-1"></i>Beklemede</span>
                                    @endif
                                </td>
                                <td class="font-monospace">
                                    <strong class="text-dark fw-bold d-block">📅 {{ $consent->created_at->format('d.m.Y') }}</strong>
                                    <small class="text-muted fw-bold">{{ $consent->created_at->format('H:i') }}</small>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('kvkk-consents.show', $consent->id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="İncele & OTP Doğrulama">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('kvkk-consents.edit', $consent->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('kvkk-consents.destroy', $consent->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'KVKK Onay Kaydını Sil?', 'Bu rıza/onay kaydı silinecektir.')" title="Sil">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="ri-shield-keyhole-line display-4 text-warning mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">KVKK Onay Talebi Bulunmuyor</h6>
                                    <p class="small text-muted mb-0">Üstteki "+ Yeni Onay Talebi" butonuna tıklayarak ilk dijital rıza kaydınızı oluşturabilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-3 d-flex justify-content-center">
            {{ $consents->links() }}
        </div>
    </div>

</div>
@endsection
