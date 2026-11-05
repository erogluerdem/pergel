@extends('layouts.master')

@section('title', 'Danışman Değişikliği Detayı — ' . ($consultantChange->customer->name ?? 'Müşteri'))

@push('styles')
<style>
    .cc-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .cc-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: ccShowGlow 4s linear infinite;
    }

    @keyframes ccShowGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .cc-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-ccshow {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-ccshow:hover {
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

    .transfer-hero-flow {
        display: inline-flex;
        align-items: center;
        gap: 1.25rem;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(10px);
        padding: 0.85rem 1.75rem;
        border-radius: 50px;
        border: 1px solid rgba(245, 158, 11, 0.4);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="cc-show-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill cc-badge-pill">
                        <i class="ri-user-shared-fill text-warning fs-5"></i> CRM & PORTFÖY DEVRİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        📅 {{ $consultantChange->change_date ? $consultantChange->change_date->format('d.m.Y') : '-' }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-3">👤 {{ $consultantChange->customer->name ?? 'Müşteri Kaydı' }}</h2>

                <div class="transfer-hero-flow text-white mb-1">
                    <div>
                        <small class="text-warning d-block fw-bold" style="font-size: 11px;">ESKİ TEMSİLCİ</small>
                        <strong class="fs-6 text-white fw-extrabold">🔴 {{ $consultantChange->oldConsultant->name ?? 'Belirtilmedi' }}</strong>
                    </div>
                    <i class="ri-arrow-right-line fs-3 text-warning"></i>
                    <div>
                        <small class="text-success d-block fw-bold" style="font-size: 11px;">YENİ ATANAN TEMSİLCİ</small>
                        <strong class="fs-6 text-white fw-extrabold">🟢 {{ $consultantChange->newConsultant->name ?? 'Belirtilmedi' }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('consultant-changes.edit', $consultantChange->id) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-edit-fill me-1"></i>Düzenle
                    </a>
                    <a href="{{ route('consultant-changes.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Tüm Değişiklikler
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SPEC KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-ccshow h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">MÜŞTERİ UNVANI</small>
                        <div class="fw-extrabold text-primary text-truncate" style="font-size: 1.3rem;">{{ $consultantChange->customer->name ?? '-' }}</div>
                        <small class="text-primary fw-bold"><i class="ri-user-3-fill me-1"></i>Devredilen Hesap</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-user-3-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-ccshow h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">DEĞİŞİKLİK TARİHİ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.5rem;">
                            {{ $consultantChange->change_date ? $consultantChange->change_date->format('d.m.Y') : '-' }}
                        </div>
                        <small class="text-success fw-bold"><i class="ri-calendar-check-fill me-1"></i>Resmi Atama Tarihi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-calendar-check-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-ccshow h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">GEÇEN SÜRE</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.5rem;">
                            {{ $consultantChange->days_ago }} Gün Önce
                        </div>
                        <small class="text-info fw-bold"><i class="ri-time-fill me-1"></i>Devirden Bu Yana</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-time-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    <div class="row">
        <!-- LEFT COLUMN: TRANSFER SPECS & REASON -->
        <div class="col-lg-8">
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-file-list-3-line text-warning fs-5"></i>Değişiklik Nedeni & Detayı
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <div class="p-4 rounded-3 text-dark fw-bold mb-4" style="background: #f8fafc; border: 1px solid #cbd5e1; line-height: 1.8;">
                        <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 11px;"><i class="ri-question-fill text-warning me-1"></i>Gerekçe / Neden:</small>
                        <span class="fs-5 text-dark font-sans">{{ $consultantChange->reason ?? 'Gerekçe belirtilmemiş.' }}</span>
                    </div>

                    @if($consultantChange->notes)
                        <div class="p-4 rounded-3 text-dark fw-semibold" style="background: #fffbeb; border: 1px solid #fde68a; line-height: 1.8;">
                            <small class="text-warning d-block text-uppercase fw-bold mb-1" style="font-size: 11px;"><i class="ri-draft-fill me-1"></i>Ek Notlar:</small>
                            <span class="fs-6 text-dark font-sans">{{ $consultantChange->notes }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: AUDIT & ACTIONS -->
        <div class="col-lg-4">
            <!-- AUDIT INFO CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-shield-user-line text-warning fs-5"></i>Sistem Audit Bilgisi
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Değişikliği Gerçekleştiren</small>
                        <strong class="fs-6 text-dark fw-extrabold"><i class="ri-user-star-fill text-primary me-1"></i>{{ $consultantChange->changedBy->name ?? 'Sistem Yöneticisi' }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Sistem Kayıt Tarihi</small>
                        <span class="fw-bold text-dark font-monospace">📅 {{ $consultantChange->created_at ? $consultantChange->created_at->format('d.m.Y H:i') : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- DELETE ACTION CARD -->
            <div class="card custom-show-card">
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('consultant-changes.edit', $consultantChange->id) }}" class="btn btn-warning text-dark rounded-pill py-2.5 fw-extrabold shadow-sm">
                            <i class="ri-edit-fill me-1"></i>Kaydı Düzenle
                        </a>
                        <form action="{{ route('consultant-changes.destroy', $consultantChange->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill py-2.5 fw-extrabold" onclick="return confirmDelete(this, 'Danışman Değişikliğini Sil?', 'Bu danışman devir kaydı tamamen silinecektir.')">
                                <i class="ri-delete-bin-line me-1"></i>Kaydı Sil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
