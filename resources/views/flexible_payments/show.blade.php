@extends('layouts.master')

@section('title', 'Ödeme Planı — ' . ($paymentPlan->name ?? 'Şablon Detayı'))

@push('styles')
<style>
    .fp-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .fp-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: fpShowGlow 4s linear infinite;
    }

    @keyframes fpShowGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .fp-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-fpshow {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-fpshow:hover {
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
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="fp-show-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    @if($paymentPlan->down_payment_percentage >= 100)
                        <span class="badge bg-primary text-white rounded-pill px-3 py-2 font-monospace fw-bold">🚀 %100 PEŞİN ŞABLON</span>
                    @else
                        <span class="badge bg-success text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #16a34a !important;">🟢 %{{ number_format($paymentPlan->down_payment_percentage, 0) }} PEŞİNATLI VADE</span>
                    @endif
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill fp-badge-pill font-monospace">
                        <i class="ri-calendar-event-fill text-warning fs-5"></i> {{ $paymentPlan->installment_count }} Taksit
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">💳 {{ $paymentPlan->name ?? ($paymentPlan->plan_no ?? 'İsimsiz Şablon') }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    <i class="ri-time-line text-warning me-1"></i> Ödeme Periyodu: <strong class="text-white fw-bold">{{ $paymentPlan->interval_days ?? $paymentPlan->payment_interval_days ?? 30 }} Günde Bir Taksit Vadesi</strong>
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('flexible-payments.edit', $paymentPlan) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-edit-fill me-1"></i>Düzenle
                    </a>
                    <a href="{{ route('flexible-payments.scenario_builder') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-calculator-line me-1"></i>Senaryo Test Et
                    </a>
                    <a href="{{ route('flexible-payments.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Şablonlar Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FINANCIAL KPI SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-fpshow h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM TAKSİT ADEDİ</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.7rem;">{{ $paymentPlan->installment_count }} Taksit</div>
                        <small class="text-primary fw-bold"><i class="ri-calendar-event-line me-1"></i>Dönemsel Vade Sayısı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-calendar-event-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-fpshow h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">PEŞİNAT ORANI</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;">%{{ number_format($paymentPlan->down_payment_percentage, 0) }}</div>
                        <small class="text-success fw-bold"><i class="ri-percent-line me-1"></i>Peşin Ödeme Oranı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-percent-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-fpshow h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TAHMİNİ VADE SÜRESİ</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.7rem;">{{ ($paymentPlan->installment_count - 1) * ($paymentPlan->interval_days ?? $paymentPlan->payment_interval_days ?? 30) }} Gün</div>
                        <small class="text-warning fw-bold"><i class="ri-time-line me-1"></i>Toplam Vade Gün Adedi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-history-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN DETAILS GRID -->
    <div class="row">
        <!-- LEFT COLUMN: DESCRIPTION & RULES -->
        <div class="col-lg-7">
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-file-text-line text-warning fs-5"></i>Şablon Tanımı ve Kuralları
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="mb-4">
                        <small class="text-muted d-block fw-bold text-uppercase mb-1" style="font-size: 11px;">Açıklama</small>
                        <p class="fs-6 text-dark fw-semibold mb-0" style="line-height: 1.6;">{{ $paymentPlan->description ?? 'Bu ödeme şablonu için ekstra açıklama bulunmamaktadır.' }}</p>
                    </div>

                    @if($paymentPlan->notes)
                        <div class="p-3.5 rounded-3 border mb-4" style="background: #fffbeb; border-left: 4px solid #d97706 !important; color: #0f172a;">
                            <div class="fw-extrabold text-dark mb-1"><i class="ri-sticky-note-fill text-warning me-1"></i>Özel Notlar & Şartlar:</div>
                            <p class="mb-0 text-muted fw-semibold small">{{ $paymentPlan->notes }}</p>
                        </div>
                    @endif

                    <div class="p-3.5 rounded-3 border" style="background: #f0fdf4; border-left: 4px solid #16a34a !important; color: #0f172a;">
                        <div class="d-flex align-items-center gap-2 text-dark fw-extrabold mb-1">
                            <i class="ri-checkbox-circle-fill text-success fs-5"></i>Şablon Kullanılabilirlik Durumu
                        </div>
                        <p class="mb-0 text-muted fw-semibold small">
                            {{ $paymentPlan->is_template ? 'Bu plan genel bir şablon olarak kayıtlıdır ve tüm yeni satış işlemlerinde ve tekliflerde seçilebilir.' : 'Bu plan özel bir satış/fatura ödeme planı olarak yapılandırılmıştır.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: SAMPLE SIMULATION & META -->
        <div class="col-lg-5">
            @php
                $sampleTotal = 10000000;
                $sampleDown = ($sampleTotal * $paymentPlan->down_payment_percentage) / 100;
                $sampleRemaining = $sampleTotal - $sampleDown;
                $sampleInstallment = $paymentPlan->installment_count > 0 ? ($sampleRemaining / $paymentPlan->installment_count) : 0;
            @endphp

            <!-- MOCK CALCULATION SIMULATION CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-calculator-fill text-warning fs-5"></i>Örnek Simülasyon (10.000.000 ₺)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-bottom">
                        <span class="text-muted fw-semibold">Örnek Satış Tutarı:</span>
                        <strong class="text-primary font-monospace fs-6">₺{{ number_format($sampleTotal, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-bottom">
                        <span class="text-muted fw-semibold">Peşinat Tutarı (%{{ number_format($paymentPlan->down_payment_percentage, 0) }}):</span>
                        <strong class="text-success font-monospace fs-6">₺{{ number_format($sampleDown, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2.5 border-bottom">
                        <span class="text-muted fw-semibold">Kalan Taksitlendirilen Bakiye:</span>
                        <strong class="text-dark font-monospace fs-6">₺{{ number_format($sampleRemaining, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2.5 mt-2">
                        <span class="fw-extrabold text-dark">Dönemsel Taksit Tutarı:</span>
                        <strong class="fs-5 text-warning font-monospace">₺{{ number_format($sampleInstallment, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <!-- META & DELETE ACTIONS -->
            <div class="card custom-show-card">
                <div class="card-body p-4">
                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Sistem Kayıt Tarihi</small>
                    <span class="fw-bold text-dark d-block mb-3 font-monospace">📅 {{ $paymentPlan->created_at ? $paymentPlan->created_at->format('d.m.Y H:i') : '-' }}</span>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('flexible-payments.edit', $paymentPlan) }}" class="btn btn-warning text-dark rounded-pill py-2.5 fw-extrabold shadow-sm">
                            <i class="ri-edit-fill me-1"></i>Şablon Şartlarını Düzenle
                        </a>
                        <form action="{{ route('flexible-payments.destroy', $paymentPlan) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill py-2.5 fw-extrabold" onclick="return confirmDelete(this, 'Ödeme Şablonunu Sil?', 'Bu ödeme şablonu kalıcı olarak silinecektir.')">
                                <i class="ri-delete-bin-line me-1"></i>Şablonu Sil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
