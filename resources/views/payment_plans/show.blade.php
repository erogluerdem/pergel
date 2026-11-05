@extends('layouts.master')

@section('title', 'Ödeme Planı Detayı — ' . $paymentPlan->plan_no)

@push('styles')
<style>
    .pp-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .pp-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: ppShowGlow 4s linear infinite;
    }

    @keyframes ppShowGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .pp-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-ppshow {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-ppshow:hover {
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

    .custom-pp-table thead,
    .custom-pp-table thead tr,
    .custom-pp-table thead th {
        background-color: #0f172a !important;
        color: #ffffff !important;
        padding: 14px 16px !important;
        font-weight: 800 !important;
        font-size: 0.85rem !important;
        border-bottom: 2px solid #d97706 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        --bs-table-bg: #0f172a !important;
        --bs-table-color: #ffffff !important;
    }

    .custom-pp-table td {
        padding: 14px 16px !important;
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
    <div class="pp-show-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    @if($paymentPlan->status == 'completed')
                        <span class="badge bg-success text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #16a34a !important;">🎉 TAMAMLANDI</span>
                    @elseif($paymentPlan->status == 'cancelled')
                        <span class="badge bg-danger text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #ef4444 !important;">❌ İPTAL EDİLDİ</span>
                    @else
                        <span class="badge bg-primary text-white rounded-pill px-3 py-2 font-monospace fw-bold">✓ AKTİF ÖDEME PLANIDIR</span>
                    @endif

                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill pp-badge-pill font-monospace">
                        <i class="ri-hashtag text-warning fs-5"></i> {{ $paymentPlan->plan_no }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">📜 {{ $paymentPlan->name ?? ('Ödeme Planı - ' . $paymentPlan->plan_no) }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    <i class="ri-user-3-line text-warning me-1"></i> Müşteri: <strong class="text-white fw-bold">{{ $paymentPlan->invoice->customer->name ?? 'Müşteri Eşleşmedi' }}</strong> |
                    <i class="ri-file-paper-line text-warning me-1"></i> Fatura No: <strong class="text-white fw-bold">{{ $paymentPlan->invoice->no ?? 'Fatura Yok' }}</strong>
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('payment-plans.edit', $paymentPlan->id) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-edit-fill me-1"></i>Düzenle
                    </a>
                    <a href="{{ route('payment-plans.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Planlar Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SPEC KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-ppshow h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM VADELENDİRİLEN TUTAR</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;">₺{{ number_format($paymentPlan->total_amount, 2, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-wallet-3-fill me-1"></i>Planlanan Toplam Hacim</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-ppshow h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TAKSİT YAPISI & TUTARI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.5rem;">
                            {{ $paymentPlan->installment_count }} Taksit × ₺{{ number_format($paymentPlan->total_amount / max(1, $paymentPlan->installment_count), 0, ',', '.') }}
                        </div>
                        <small class="text-primary fw-bold"><i class="ri-calendar-event-fill me-1"></i>Aylık Taksit Bedeli</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-calendar-todo-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-ppshow h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">ÖDEME PERİYODU & BAŞLANGIÇ</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.4rem;">
                            {{ $paymentPlan->start_date ? $paymentPlan->start_date->format('d.m.Y') : '-' }}
                        </div>
                        <small class="text-warning fw-bold"><i class="ri-time-fill me-1"></i>{{ $paymentPlan->payment_interval_days ?? 30 }} Günde Bir Ödeme</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-time-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN DETAILS GRID -->
    <div class="row">
        <!-- LEFT COLUMN: INSTALLMENT TABLE & NOTES -->
        <div class="col-lg-8">
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-calendar-event-line text-warning fs-5"></i>Taksit Ödeme Çizelgesi
                    </h5>
                    <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                        {{ $paymentPlan->installments->count() }} Taksit Kaydı
                    </span>
                </div>

                <div class="card-body p-0">
                    @if($paymentPlan->installments->isEmpty())
                        <div class="p-4 text-center text-muted">
                            <i class="ri-information-line fs-3 text-warning mb-1 d-block"></i>
                            <span class="fw-bold text-dark">Bu ödeme planına tanımlı bağımsız taksit kaydı bulunmuyor.</span>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table custom-pp-table align-middle mb-0">
                                <thead style="background-color: #0f172a !important;">
                                    <tr style="background-color: #0f172a !important;">
                                        <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Taksit No</th>
                                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Vade Tarihi</th>
                                        <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Taksit Tutarı</th>
                                        <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Ödenen Tutar</th>
                                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Ödeme Tarihi</th>
                                        <th class="pe-4 text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paymentPlan->installments as $inst)
                                        <tr>
                                            <td class="ps-4 py-3 font-monospace">
                                                <span class="badge bg-dark text-warning border border-warning rounded-pill px-3 py-1 font-monospace fw-bold">
                                                    #{{ $inst->installment_number }}
                                                </span>
                                            </td>
                                            <td class="font-monospace fw-bold text-dark">
                                                📅 {{ $inst->due_date ? $inst->due_date->format('d.m.Y') : '-' }}
                                            </td>
                                            <td class="text-end font-monospace">
                                                <strong class="text-dark fs-6 fw-extrabold">₺{{ number_format($inst->amount, 2, ',', '.') }}</strong>
                                            </td>
                                            <td class="text-end font-monospace">
                                                @if($inst->paid_amount > 0)
                                                    <span class="text-success fw-extrabold font-monospace">₺{{ number_format($inst->paid_amount, 2, ',', '.') }}</span>
                                                @else
                                                    <span class="text-muted">₺0,00</span>
                                                @endif
                                            </td>
                                            <td class="font-monospace fw-bold">
                                                {{ $inst->paid_date ? $inst->paid_date->format('d.m.Y') : '-' }}
                                            </td>
                                            <td class="pe-4 text-center font-monospace">
                                                @if($inst->status == 'paid')
                                                    <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">✓ ÖDENDİ</span>
                                                @elseif($inst->status == 'partial')
                                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">⏳ KİSMİ</span>
                                                @elseif($inst->is_overdue)
                                                    <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #ef4444 !important;">🚨 GECİKMİŞ</span>
                                                @else
                                                    <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">⏳ BEKLEYEN</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            @if($paymentPlan->notes || $paymentPlan->description)
                <div class="card custom-show-card">
                    <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                        <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                            <i class="ri-file-text-line text-warning fs-5"></i>Açıklama & Detay Notları
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="p-4 rounded-3 text-dark fw-semibold" style="background: #f8fafc; border: 1px solid #cbd5e1; line-height: 1.8;">
                            {{ $paymentPlan->description ?? $paymentPlan->notes }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- RIGHT COLUMN: CUSTOMER & INVOICE SPECS -->
        <div class="col-lg-4">
            <!-- CUSTOMER & INVOICE CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-user-shared-line text-warning fs-5"></i>Fatura & Müşteri Detayı
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($paymentPlan->invoice)
                        <div class="mb-3">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Müşteri Unvanı</small>
                            <strong class="fs-5 text-dark fw-extrabold">👤 {{ $paymentPlan->invoice->customer->name ?? 'Belirtilmedi' }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Bağlı Fatura No</small>
                            <span class="fw-extrabold text-primary font-monospace fs-6">🧾 {{ $paymentPlan->invoice->no }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Fatura Genel Toplamı</small>
                            <span class="fw-extrabold text-dark font-monospace fs-6">₺{{ number_format($paymentPlan->invoice->amount, 2, ',', '.') }}</span>
                        </div>
                    @else
                        <span class="text-muted fw-bold">Bağlı fatura kaydı bulunmamaktadır.</span>
                    @endif
                </div>
            </div>

            <!-- META & DELETE ACTIONS CARD -->
            <div class="card custom-show-card">
                <div class="card-body p-4">
                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Sistem Kayıt Tarihi</small>
                    <span class="fw-bold text-dark d-block mb-3 font-monospace">📅 {{ $paymentPlan->created_at ? $paymentPlan->created_at->format('d.m.Y H:i') : '-' }}</span>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('payment-plans.edit', $paymentPlan->id) }}" class="btn btn-warning text-dark rounded-pill py-2.5 fw-extrabold shadow-sm">
                            <i class="ri-edit-fill me-1"></i>Planı Düzenle
                        </a>
                        <form action="{{ route('payment-plans.destroy', $paymentPlan->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill py-2.5 fw-extrabold" onclick="return confirmDelete(this, 'Ödeme Planını Sil?', 'Bu taksit/ödeme planı silinecektir.')">
                                <i class="ri-delete-bin-line me-1"></i>Planı Sil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
