@extends('layouts.master')

@section('title', 'Maaş Pusulası & Bordro Detayı')

@section('topbar-title', 'Personel Bordroları - Detay')

@push('styles')
<style>
    .hr-payroll-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #4f46e5 50%, #4338ca 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .hr-payroll-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-payroll-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $empName = $payroll->employee->first_name ? ($payroll->employee->first_name . ' ' . $payroll->employee->last_name) : ($payroll->employee->name ?? 'Ahmet Yılmaz');
    $gross = $payroll->gross_salary ?? 85000.00;
    $net = $payroll->net_salary ?? 62450.00;
@endphp

<!-- Hero Banner -->
<div class="hr-payroll-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.hr.payroll') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Bordrolar Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    Dönem: {{ $payroll->period ?: '2026-08' }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Maaş Pusulası & Bordro Tahakkuku</h1>
            <p class="text-white-50 mb-0 fs-6">Personel: {{ $empName }} — Pozisyon: {{ $payroll->employee->position ?? 'Saha Ekibi' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.hr.payroll.edit', $id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>Bordroyu Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="hr-payroll-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $empName }} — Bordro Hesabı</h3>
                    <small class="text-muted">Ödeme Tarihi: {{ $payroll->payment_date ? \Carbon\Carbon::parse($payroll->payment_date)->format('d.m.Y') : '-' }}</small>
                </div>
                <div>
                    @if($payroll->status === 'paid')
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Ödendi (Banka)</span>
                    @elseif($payroll->status === 'approved')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Onaylandı</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Onay Bekliyor</span>
                    @endif
                </div>
            </div>

            <!-- Summary Specs -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Brüt Maaş</span>
                    <h3 class="fw-extrabold mb-0 text-indigo" style="color:#4f46e5;">₺{{ number_format($gross, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Net Ödenecek Maaş</span>
                    <h3 class="fw-extrabold mb-0 text-success">₺{{ number_format($net, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">SGK Primi Kesintisi</span>
                    <h4 class="fw-bold mb-0 text-warning">₺{{ number_format($payroll->sgk_deduction ?? 12750.00, 2, ',', '.') }}</h4>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Gelir Vergisi</span>
                    <h4 class="fw-bold mb-0 text-danger">₺{{ number_format($payroll->tax_deduction ?? 9800.00, 2, ',', '.') }}</h4>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Hakediş & Kesinti Detay Tablosu</h6>
            <div class="table-responsive border rounded-3 mb-4">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Açıklama Kalemi</th>
                            <th class="text-end">Tutar (₺)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Taban Brüt Ücret</td>
                            <td class="text-end fw-bold">₺{{ number_format($gross, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Fazla Mesai Hakedişi</td>
                            <td class="text-end text-success fw-bold">+ ₺{{ number_format($payroll->overtime_bonus ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>SGK İşçi Payı Kesintisi</td>
                            <td class="text-end text-danger">- ₺{{ number_format($payroll->sgk_deduction ?? 12750.00, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Gelir Vergisi Kesintisi</td>
                            <td class="text-end text-danger">- ₺{{ number_format($payroll->tax_deduction ?? 9800.00, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Avans Mahsubu Kesintisi</td>
                            <td class="text-end text-warning">- ₺{{ number_format($payroll->advance_deduction ?? 0, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="table-light fw-bold fs-6">
                            <td>NET HESABA YATACAK TUTAR</td>
                            <td class="text-end text-success fs-5">₺{{ number_format($net, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
