@extends('layouts.master')

@section('title', 'Taksit & Tahsilat Portföyü')

@push('styles')
<style>
    .ins-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .ins-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: insGlow 4s linear infinite;
    }

    @keyframes insGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .ins-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-ins {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-ins:hover {
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

    .custom-ins-table thead,
    .custom-ins-table thead tr,
    .custom-ins-table thead th {
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

    .custom-ins-table td {
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
    <div class="ins-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill ins-badge-pill">
                        <i class="ri-money-dollar-circle-fill text-warning fs-5"></i> FİNANS & TAKSİT TAKİBİ
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $installments->total() }} Kayıtlı Taksit
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Müşteri Taksit Portföyü</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Tüm aktif ödeme planlarına ait taksit vadeleri, tahsilat durumu ve gecikmiş ödeme takibi.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('installments.create') }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-add-circle-fill me-1"></i>+ Yeni Taksit Ekle
                    </a>
                    <a href="{{ route('installments.payment_status_report') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-bar-chart-2-fill me-1"></i>Ödeme Raporu
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
            <div class="card custom-kpi-card-ins h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">BEKLEYEN TAHSİLAT</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.7rem;">₺{{ number_format($stats['total_pending'] ?? 0, 0, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-time-line me-1"></i>Vadesi Bekleyen Taksitler</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-time-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-ins h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">GECİKMİŞ TAKSİT TUTARI</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.7rem;">₺{{ number_format($stats['total_overdue'] ?? 0, 0, ',', '.') }}</div>
                        <small class="text-danger fw-bold"><i class="ri-alarm-warning-fill me-1"></i>Aşılmış Vade Toplamı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fef2f2 !important; border: 1px solid #fecaca !important;">
                        <i class="ri-alarm-warning-fill fs-2" style="color: #dc2626 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-ins h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TAHSİL EDİLEN TUTAR</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;">₺{{ number_format($stats['total_paid'] ?? 0, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Gerçekleşen Tahsilatlar</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-ins h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">YAKLAŞAN (7 GÜN)</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.8rem;">{{ $stats['upcoming_count'] ?? 0 }} Taksit</div>
                        <small class="text-info fw-bold"><i class="ri-calendar-check-fill me-1"></i>Bu Haftaki Vadeler</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-calendar-check-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-calendar-event-line text-warning fs-5"></i>Müşteri Taksit Çizelgesi
            </h5>
            <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                {{ $installments->total() }} Taksit
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-ins-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">No</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İlişkili Fatura & Müşteri</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Vade Tarihi</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Taksit Tutarı</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Tahsil Edilen & İlerleme</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Durum</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($installments as $installment)
                            @php
                                $paidRatio = $installment->amount > 0 ? min(100, round(($installment->paid_amount / $installment->amount) * 100)) : 0;
                                $isOverdue = $installment->status === 'pending' && $installment->due_date && $installment->due_date->isPast();
                            @endphp
                            <tr>
                                <td class="ps-4 py-3 font-monospace">
                                    <span class="badge bg-dark text-warning border border-warning rounded-pill px-3 py-1 font-monospace fw-bold">
                                        #{{ $installment->installment_number }}
                                    </span>
                                </td>
                                <td>
                                    @if($installment->invoice)
                                        <strong class="text-dark d-block fs-6 fw-extrabold"><i class="ri-file-paper-line me-1 text-primary"></i>{{ $installment->invoice->no }}</strong>
                                        <small class="text-muted fw-bold">👤 {{ $installment->invoice->customer->name ?? 'Müşteri Eşleşmedi' }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="font-monospace">
                                    <strong class="text-dark fw-bold">📅 {{ $installment->due_date ? $installment->due_date->format('d.m.Y') : '-' }}</strong>
                                    @if($isOverdue)
                                        <small class="d-block text-danger fw-extrabold"><i class="ri-alarm-warning-fill me-1"></i>Vadesi Geçmiş</small>
                                    @endif
                                </td>
                                <td class="text-end font-monospace">
                                    <strong class="fs-6 text-dark font-monospace fw-extrabold">₺{{ number_format($installment->amount, 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 10px; border-radius: 6px; background-color: #e2e8f0;">
                                            <div class="progress-bar bg-{{ $paidRatio == 100 ? 'success' : ($paidRatio > 0 ? 'warning' : 'secondary') }}" 
                                                 role="progressbar" style="width: {{ $paidRatio }}%"></div>
                                        </div>
                                        <small class="fw-extrabold text-dark font-monospace">₺{{ number_format($installment->paid_amount, 0, ',', '.') }} (%{{ $paidRatio }})</small>
                                    </div>
                                </td>
                                <td class="text-center font-monospace">
                                    @if($installment->status == 'paid')
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">✓ ÖDENDİ</span>
                                    @elseif($installment->status == 'partial')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">⏳ KİSMEN ÖDENDİ</span>
                                    @elseif($isOverdue)
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #ef4444 !important;">🚨 GECİKMİŞ</span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">⏳ BEKLEYEN</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('installments.show', $installment->id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="İncele">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('installments.edit', $installment->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="ri-calendar-event-line display-4 text-warning mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Taksit Kaydı Bulunmuyor</h6>
                                    <p class="small text-muted mb-0">Üstteki "+ Yeni Taksit Ekle" butonuna tıklayarak ilk taksit kaydınızı oluşturabilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-3 d-flex justify-content-center">
            {{ $installments->links() }}
        </div>
    </div>

</div>
@endsection
