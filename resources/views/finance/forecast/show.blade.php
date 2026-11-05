@extends('layouts.master')

@section('title', 'Tahmin Detayı — ' . ($forecast->forecast_date ? $forecast->forecast_date->format('M Y') : 'Nakit Akış'))

@section('topbar-title', 'Nakit Akış - Tahmin Detayı')

@push('styles')
<style>
    .forecast-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #047857 50%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .forecast-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .forecast-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .forecast-spec-box {
        border-radius: 14px;
        padding: 1.25rem;
        background: rgba(15, 23, 42, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    html[data-bs-theme="dark"] .forecast-spec-box {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }
</style>
@endpush

@section('content')

@php
    $plannedNet = $forecast->inflow_planned - $forecast->outflow_planned;
    $actualNet = $forecast->inflow_actual - $forecast->outflow_actual;
@endphp

<!-- Hero Banner -->
<div class="forecast-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.forecast.dashboard', ['project_id' => $project->id]) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Tahmin Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $forecast->forecast_date ? $forecast->forecast_date->format('F Y') : '-' }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Nakit Akış Tahmin Detayı</h1>
            <p class="text-white-50 mb-0 fs-6">Proje: {{ $project->name ?? 'Proje Atanmamış' }} — Para Birimi: {{ $forecast->currency }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.forecast.edit', $forecast->id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>Tahmini Düzenle
            </a>
        </div>
    </div>
</div>

<!-- 4 Spec Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="forecast-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Devir Bakiyesi (Açılış)</span>
            <h3 class="fw-extrabold mb-0 text-dark">₺{{ number_format($forecast->opening_balance, 2, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="forecast-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Planlanan Tahsilat (Giriş)</span>
            <h3 class="fw-extrabold mb-0 text-success">+₺{{ number_format($forecast->inflow_planned, 2, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="forecast-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Planlanan Ödeme (Çıkış)</span>
            <h3 class="fw-extrabold mb-0 text-danger">-₺{{ number_format($forecast->outflow_planned, 2, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="forecast-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Dönem Sonu Kapanış Bakiyesi</span>
            <h3 class="fw-extrabold mb-0 text-emerald" style="color:#059669;">₺{{ number_format($forecast->closing_balance, 2, ',', '.') }}</h3>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Breakdown & Comparison -->
    <div class="col-lg-8">
        <div class="forecast-glass-card">
            <h5 class="fw-bold text-dark mb-3"><i class="ri-scales-line text-emerald me-2" style="color:#059669;"></i>Planlanan ve Gerçekleşen Akış Karşılaştırması</h5>

            <div class="table-responsive mb-4">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Metrik</th>
                            <th>Planlanan (Hedef)</th>
                            <th>Gerçekleşen (Fiili)</th>
                            <th class="text-end">Fark (Sapma)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong class="text-dark">Nakit Girişleri (Tahsilat)</strong></td>
                            <td class="text-success fw-bold">+₺{{ number_format($forecast->inflow_planned, 2, ',', '.') }}</td>
                            <td class="text-success">+₺{{ number_format($forecast->inflow_actual, 2, ',', '.') }}</td>
                            <td class="text-end">
                                <span class="{{ ($forecast->inflow_actual - $forecast->inflow_planned) >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                    ₺{{ number_format($forecast->inflow_actual - $forecast->inflow_planned, 2, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong class="text-dark">Nakit Çıkışları (Ödemeler)</strong></td>
                            <td class="text-danger fw-bold">-₺{{ number_format($forecast->outflow_planned, 2, ',', '.') }}</td>
                            <td class="text-danger">-₺{{ number_format($forecast->outflow_actual, 2, ',', '.') }}</td>
                            <td class="text-end">
                                <span class="{{ ($forecast->outflow_actual - $forecast->outflow_planned) <= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                    ₺{{ number_format($forecast->outflow_actual - $forecast->outflow_planned, 2, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr class="table-light fw-bold">
                            <td>Net Nakit Akışı</td>
                            <td class="{{ $plannedNet >= 0 ? 'text-success' : 'text-danger' }}">₺{{ number_format($plannedNet, 2, ',', '.') }}</td>
                            <td class="{{ $actualNet >= 0 ? 'text-success' : 'text-danger' }}">₺{{ number_format($actualNet, 2, ',', '.') }}</td>
                            <td class="text-end">₺{{ number_format($actualNet - $plannedNet, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Notes -->
            <h6 class="fw-bold text-dark mb-2">Finansal Notlar & Analiz Açıklaması</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark" style="white-space: pre-line;">
                {{ $forecast->notes ?: 'Not girilmedi.' }}
            </div>
        </div>
    </div>

    <!-- Right Column: Metadata -->
    <div class="col-lg-4">
        <div class="forecast-glass-card">
            <h6 class="fw-bold text-dark mb-3"><i class="ri-shield-line text-emerald me-2" style="color:#059669;"></i>Tahmin Güven Metrikleri</h6>
            <div class="list-group list-group-flush border-0">
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Güven Seviyesi:</span>
                    <strong class="text-emerald" style="color:#059669;">%{{ $forecast->confidence_level }} ({{ $forecast->confidence_label }})</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Sapma Yüzdesi:</span>
                    <strong class="text-dark">%{{ number_format($forecast->variance_percentage, 2) }}</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Durum:</span>
                    @if($forecast->status === 'confirmed')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylı</span>
                    @elseif($forecast->status === 'revised')
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">Revize Edildi</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Taslak</span>
                    @endif
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Oluşturulma:</span>
                    <strong class="text-dark">{{ $forecast->created_at ? $forecast->created_at->format('d.m.Y H:i') : '-' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
