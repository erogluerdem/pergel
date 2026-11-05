@extends('layouts.master')

@section('title', 'Sözleşme Taksit Cetveli')

@section('topbar-title', 'Satış Raporları - Sözleşme Taksitleri')

@push('styles')
<style>
    .sales-acc-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #059669 50%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .sales-acc-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .sales-acc-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $no = $invoice->no ?? 'SAT-001';
    $amount = $invoice->amount ?? 0;
    $paid = $invoice->paid_amount ?? 0;
    $rem = $amount - $paid;
@endphp

<!-- Hero Banner -->
<div class="sales-acc-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('reports.sales.accounting_summary') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Muhasebe Özeti
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold border border-white border-opacity-25 shadow-sm">
                    {{ $no }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Satış Sözleşmesi Taksit Planı</h1>
            <p class="text-white-50 mb-0 fs-6">Müşteri: {{ $invoice->customer->name ?? '-' }} | Sözleşme Tarihi: {{ \Carbon\Carbon::parse($invoice->date ?? $invoice->created_at)->format('d.m.Y') }}</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="sales-acc-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $no }} — {{ $invoice->customer->name ?? '-' }}</h3>
                    <small class="text-muted">Taksitli Satış Ödeme Takvimi</small>
                </div>
                <div>
                    @if($rem == 0)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Tüm Taksitler Ödendi</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Taksit Ödemesi Bekliyor</span>
                    @endif
                </div>
            </div>

            <!-- Financial Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Toplam Sözleşme Tutarı</span>
                    <h3 class="fw-extrabold mb-0 text-primary">₺{{ number_format($amount, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Tahsil Edilen Toplam Taksit</span>
                    <h3 class="fw-extrabold mb-0 text-success">₺{{ number_format($paid, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Kalan Taksit Bakiyesi</span>
                    <h3 class="fw-extrabold mb-0 text-emerald" style="color:#059669;">₺{{ number_format($rem, 2, ',', '.') }}</h3>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Sözleşme Notları & Açıklama</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark mb-4">
                {{ $invoice->notes ?: 'Sözleşmeye ait ek açıklama bulunmuyor.' }}
            </div>
        </div>
    </div>
</div>

@endsection
