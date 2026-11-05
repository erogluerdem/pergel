@extends('layouts.master')

@section('title', 'Danışman Satış Detayı')

@section('topbar-title', 'Satış - Danışman Performansı')

@push('styles')
<style>
    .sales-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #6366f1 50%, #4f46e5 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .sales-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .sales-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $targetAmt = $success['target_amount'] ?? 0;
    $achievedAmt = $success['achieved_amount'] ?? 0;
    $pct = $success['achievement_percentage'] ?? 0;
@endphp

<!-- Hero Banner -->
<div class="sales-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('sales_performance.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Performans Dashboard
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">{{ $user->name }} — Satış Performansı</h1>
            <p class="text-white-50 mb-0 fs-6">E-posta: {{ $user->email }} | Dönem: {{ ucfirst(str_replace('_', ' ', $period)) }}</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="sales-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $user->name }}</h3>
                    <small class="text-muted">Performans Başarı Oranı: %{{ $pct }}</small>
                </div>
                <div>
                    @if($pct >= 100)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Hedef Tamamlandı</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Devam Ediyor</span>
                    @endif
                </div>
            </div>

            <!-- Summary Specs -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Hedeflenen Ciro</span>
                    <h3 class="fw-extrabold mb-0 text-primary">₺{{ number_format($targetAmt, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Gerçekleşen Satış Cirosu</span>
                    <h3 class="fw-extrabold mb-0 text-indigo" style="color:#6366f1;">₺{{ number_format($achievedAmt, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Fark / Kalan Tutar</span>
                    <h4 class="fw-bold mb-0 text-dark">₺{{ number_format(abs($achievedAmt - $targetAmt), 2, ',', '.') }}</h4>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-3">Danışmana Ait Satışlar & Sözleşmeler</h6>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No / Fatura</th>
                            <th>Müşteri</th>
                            <th>Tarih</th>
                            <th>Tutar (₺)</th>
                            <th>Tahsil Edilen (₺)</th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($success['invoices'] as $inv)
                            <tr>
                                <td><strong class="text-dark">#{{ $inv->no ?? $inv->id }}</strong></td>
                                <td>{{ $inv->customer->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($inv->date ?? $inv->created_at)->format('d.m.Y') }}</td>
                                <td>₺{{ number_format($inv->amount ?? 0, 2, ',', '.') }}</td>
                                <td><strong class="text-indigo" style="color:#6366f1;">₺{{ number_format($inv->paid_amount ?? 0, 2, ',', '.') }}</strong></td>
                                <td><span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1">Tamamlandı</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Bu döneme ait kayıtlı satış bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
