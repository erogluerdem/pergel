@extends('layouts.master')

@section('title', 'Taksit Ödeme Durumu Raporu')

@push('styles')
<style>
    .ins-report-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .ins-report-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: insReportGlow 4s linear infinite;
    }

    @keyframes insReportGlow {
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

    .custom-kpi-card-insreport {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-insreport:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-card-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        margin-bottom: 28px;
    }

    .custom-report-table thead,
    .custom-report-table thead tr,
    .custom-report-table thead th {
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

    .custom-report-table td {
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
    <div class="ins-report-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill ins-badge-pill">
                        <i class="ri-bar-chart-box-fill text-warning fs-5"></i> FİNANSAL ANALİZ & VADE RAPORU
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Vade & Tahsilat Analizi
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Taksit Ödeme Durumu & Risk Raporu</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Geciken taksit alacakları, önümüzdeki 30 günün ödeme takvimi ve genel tahsilat durumu.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('installments.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Tüm Taksitler Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-insreport h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">GECİKMİŞ TAKSİT HACMİ</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.7rem;">₺{{ number_format($stats['overdue_amount'] ?? 0, 2, ',', '.') }}</div>
                        <span class="badge bg-danger text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #ef4444 !important;">
                            {{ $stats['overdue_count'] ?? 0 }} Geciken Taksit
                        </span>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fef2f2 !important; border: 1px solid #fecaca !important;">
                        <i class="ri-alarm-warning-fill fs-2" style="color: #dc2626 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-insreport h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">YAKLAŞAN TAKSİT HACMİ (30 GÜN)</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.7rem;">₺{{ number_format($stats['upcoming_amount'] ?? 0, 2, ',', '.') }}</div>
                        <span class="badge bg-info text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                            {{ $stats['upcoming_count'] ?? 0 }} Yaklaşan Taksit
                        </span>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-calendar-check-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-insreport h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">BAŞARIYLA TAHSİL EDİLEN</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;">₺{{ number_format($stats['total_collected'] ?? 0, 2, ',', '.') }}</div>
                        <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #16a34a !important;">
                            Toplam Tahsilat
                        </span>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- OVERDUE INSTALLMENTS TABLE CARD -->
    <div class="card custom-card-container">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-alarm-warning-fill text-danger fs-5"></i>Gecikmiş Taksit Alacakları Listesi
            </h5>
            <span class="badge bg-danger text-white font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px; background: #ef4444 !important;">
                {{ count($overdue) }} Kayıt
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-report-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Müşteri Unvanı</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Fatura No</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Taksit No</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Geciken Tutar</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Vade Tarihi</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Gecikme Süresi</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overdue as $installment)
                            <tr>
                                <td class="ps-4 py-3">
                                    <strong class="text-dark fs-6 fw-extrabold"><i class="ri-building-line me-1 text-danger"></i>{{ $installment->invoice->customer->name ?? 'Müşteri' }}</strong>
                                </td>
                                <td><span class="font-monospace fw-bold text-dark">🧾 {{ $installment->invoice->no ?? 'Fatura Yok' }}</span></td>
                                <td class="font-monospace">
                                    <span class="badge bg-dark text-warning border border-warning rounded-pill px-3 py-1 font-monospace fw-bold">
                                        #{{ $installment->installment_number }}
                                    </span>
                                </td>
                                <td class="text-end font-monospace">
                                    <strong class="text-danger font-monospace fs-6 fw-extrabold">₺{{ number_format($installment->amount, 2, ',', '.') }}</strong>
                                </td>
                                <td class="font-monospace fw-bold text-dark">📅 {{ $installment->due_date ? $installment->due_date->format('d.m.Y') : '-' }}</td>
                                <td class="text-center font-monospace">
                                    <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #ef4444 !important;">
                                        🚨 {{ $installment->due_date ? $installment->due_date->diffInDays(now()) : 0 }} gün gecikmede
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('installments.show', $installment->id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="İncele">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="ri-checkbox-circle-fill text-success fs-3 d-block mb-1"></i>
                                    <span class="fw-bold text-dark">Harika! Gecikmiş taksit kaydı bulunmamaktadır.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- UPCOMING INSTALLMENTS TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-calendar-check-fill text-info fs-5"></i>Yaklaşan Taksitler (Sonraki 30 Gün)
            </h5>
            <span class="badge bg-info text-white font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                {{ count($upcoming) }} Kayıt
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-report-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Müşteri Unvanı</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Fatura No</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Taksit No</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Taksit Tutarı</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Vade Tarihi</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Kalan Gün</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcoming as $installment)
                            <tr>
                                <td class="ps-4 py-3">
                                    <strong class="text-dark fs-6 fw-extrabold"><i class="ri-building-line me-1 text-info"></i>{{ $installment->invoice->customer->name ?? 'Müşteri' }}</strong>
                                </td>
                                <td><span class="font-monospace fw-bold text-dark">🧾 {{ $installment->invoice->no ?? 'Fatura Yok' }}</span></td>
                                <td class="font-monospace">
                                    <span class="badge bg-dark text-warning border border-warning rounded-pill px-3 py-1 font-monospace fw-bold">
                                        #{{ $installment->installment_number }}
                                    </span>
                                </td>
                                <td class="text-end font-monospace">
                                    <strong class="text-dark font-monospace fs-6 fw-extrabold">₺{{ number_format($installment->amount, 2, ',', '.') }}</strong>
                                </td>
                                <td class="font-monospace fw-bold text-dark">📅 {{ $installment->due_date ? $installment->due_date->format('d.m.Y') : '-' }}</td>
                                <td class="text-center font-monospace">
                                    @php
                                        $daysRemaining = $installment->due_date ? now()->diffInDays($installment->due_date) : 0;
                                    @endphp
                                    <span class="badge bg-{{ $daysRemaining <= 3 ? 'warning text-dark' : 'info text-white' }} rounded-pill px-3 py-1.5 font-monospace fw-bold">
                                        ⏳ {{ $daysRemaining }} gün kaldı
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('installments.show', $installment->id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="İncele">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="ri-information-line text-info fs-3 d-block mb-1"></i>
                                    <span class="fw-bold text-dark">Yaklaşan taksit kaydı bulunmamaktadır.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
