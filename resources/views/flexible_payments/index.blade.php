@extends('layouts.master')

@section('title', 'Esnek Ödeme Planları & Vade Şablonları')

@push('styles')
<style>
    .fp-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .fp-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: fpGlow 4s linear infinite;
    }

    @keyframes fpGlow {
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

    .custom-kpi-card-fp {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-fp:hover {
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

    .custom-fp-table thead,
    .custom-fp-table thead tr,
    .custom-fp-table thead th {
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

    .custom-fp-table td {
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

    @php
        $totalPlans = $plans->total();
        $avgInstallments = round(\App\Models\PaymentPlan::avg('installment_count') ?? 0);
        $avgDownPercent = round(\App\Models\PaymentPlan::avg('down_payment_percentage') ?? 0);
        $maxInstallment = \App\Models\PaymentPlan::max('installment_count') ?? 0;
    @endphp

    <!-- HERO HEADER BANNER -->
    <div class="fp-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill fp-badge-pill">
                        <i class="ri-bank-card-line text-warning fs-5"></i> FİNANS & ESNEK ÖDEME PLANLARI
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $totalPlans }} Aktif Şablon
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Esnek Ödeme Planları & Vade Şablonları</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Müşterilerinize ve satışlarınıza özel vade, peşinat ve taksit hesaplama modellerini yapılandırın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('flexible-payments.scenario_builder') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-calculator-line me-1"></i>Senaryo Hesaplayıcı
                    </a>
                    <a href="{{ route('flexible-payments.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Ödeme Planı
                    </a>
                </div>
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

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-fp h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">AKTİF ŞABLON SAYISI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $totalPlans }} Şablon</div>
                        <small class="text-primary fw-bold"><i class="ri-article-line me-1"></i>Kullanılabilir Matrahlar</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-bank-card-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-fp h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">ORTALAMA TAKSİT</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $avgInstallments }} Taksit</div>
                        <small class="text-success fw-bold"><i class="ri-calendar-event-fill me-1"></i>Portföy Vade Ortalama</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-calendar-event-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-fp h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">ORTALAMA PEŞİNAT %</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">%{{ $avgDownPercent }}</div>
                        <small class="text-warning fw-bold"><i class="ri-percent-line me-1"></i>Peşin Ödeme Tabanı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-percent-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-fp h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">MAKSİMUM VADE</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.8rem;">{{ $maxInstallment }} Ay</div>
                        <small class="text-info fw-bold"><i class="ri-time-line me-1"></i>Üst Vade Sınırı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-history-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-file-list-3-line text-warning fs-5"></i>Ödeme Şablonları Kataloğu Cetveli
            </h5>
            <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                {{ $plans->total() }} Kayıt
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-fp-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Plan / Şablon Adı</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Vade / Taksit</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Ödeme Periyodu</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Peşinat Yüzdesi</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Şablon Durumu</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($plans as $plan)
                            <tr>
                                <td class="ps-4 py-3">
                                    <span class="fw-extrabold text-dark fs-6 d-block">💳 {{ $plan->name ?? ($plan->plan_no ?? 'İsimsiz Plan') }}</span>
                                    @if($plan->description)
                                        <small class="text-muted fw-semibold text-truncate d-inline-block" style="max-width:320px;">{{ $plan->description }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-extrabold" style="font-size: 12px;">
                                        {{ $plan->installment_count }} Taksit
                                    </span>
                                </td>
                                <td class="font-monospace text-dark fw-bold" style="font-size: 13px;">
                                    📅 {{ $plan->interval_days ?? $plan->payment_interval_days ?? 30 }} Gün Vade
                                </td>
                                <td class="text-center font-monospace">
                                    @if($plan->down_payment_percentage >= 100)
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">🚀 %100 PEŞİN</span>
                                    @elseif($plan->down_payment_percentage >= 35)
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">🟢 %{{ number_format($plan->down_payment_percentage, 0) }} PEŞİNAT</span>
                                    @else
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">🟡 %{{ number_format($plan->down_payment_percentage, 0) }} PEŞİNAT</span>
                                    @endif
                                </td>
                                <td class="text-center font-monospace">
                                    @if($plan->is_template)
                                        <span class="badge bg-info text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Genel Şablon</span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">Özel Fatura Planı</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('flexible-payments.show', $plan) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="İncele">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('flexible-payments.edit', $plan) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('flexible-payments.destroy', $plan) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'Ödeme Planı Şablonunu Sil?', 'Bu ödeme planı şablonu silinecektir.')" title="Sil">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="ri-bank-card-line display-4 text-warning mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Henüz Ödeme Şablonu Bulunmuyor</h6>
                                    <p class="small text-muted mb-0">Üstteki "+ Yeni Ödeme Planı" butonuna tıklayarak ilk planınızı tanımlayabilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-3 d-flex justify-content-center">
            {{ $plans->links() }}
        </div>
    </div>

</div>
@endsection
