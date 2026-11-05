@extends('layouts.master')

@section('title', 'Varlık & Ekipman Detayı')

@section('topbar-title', 'Tesis - Varlık Detayı')

@push('styles')
<style>
    .facility-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #059669 50%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .facility-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .facility-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $code = $asset->asset_code ?? 'AST-VRF-001';
    $name = $asset->name ?? 'VRF Merkezi İklimlendirme & Chiller Santrali';
    $price = $asset->purchase_price ?? 850000.00;
    $currVal = $asset->current_value ?? 760000.00;
@endphp

<!-- Hero Banner -->
<div class="facility-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('facility.assets') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Varlık Parkı
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $code }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">{{ $name }}</h1>
            <p class="text-white-50 mb-0 fs-6">Kategori: {{ $asset->category ?? 'HVAC' }} — Lokasyon: {{ $asset->location ?? 'Genel Alan' }}</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="facility-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $name }}</h3>
                    <small class="text-muted">Marka/Model: {{ $asset->brand ?? '-' }} {{ $asset->model ?? '' }} | Seri No: {{ $asset->serial_number ?? '-' }}</small>
                </div>
                <div>
                    @if($asset->status === 'active')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Aktif Çalışıyor</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Bakımda</span>
                    @endif
                </div>
            </div>

            <!-- Summary Specs -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Satın Alma Değeri</span>
                    <h3 class="fw-extrabold mb-0 text-primary">₺{{ number_format($price, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Güncel Rayiç Değeri</span>
                    <h4 class="fw-bold mb-0 text-dark">₺{{ number_format($currVal, 2, ',', '.') }}</h4>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Son Bakım Tarihi</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $asset->last_maintenance_date ? \Carbon\Carbon::parse($asset->last_maintenance_date)->format('d.m.Y') : '-' }}</h4>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Sonraki Bakım Tarihi</span>
                    <h4 class="fw-bold mb-0 text-emerald" style="color:#059669;">{{ $asset->next_maintenance_date ? \Carbon\Carbon::parse($asset->next_maintenance_date)->format('d.m.Y') : '-' }}</h4>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Bakım Geçmişi & İş Emirleri</h6>
            <div class="table-responsive border rounded-3 mb-4">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Bakım Başlığı</th>
                            <th>Tip</th>
                            <th>Tarih</th>
                            <th>Maliyet (₺)</th>
                            <th class="text-end pe-3">Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenanceHistory as $mh)
                            <tr>
                                <td class="ps-3 fw-bold text-dark">{{ $mh->title }}</td>
                                <td><span class="badge bg-info bg-opacity-10 text-info border px-2 py-1 small">{{ $mh->type }}</span></td>
                                <td>{{ $mh->scheduled_date ? \Carbon\Carbon::parse($mh->scheduled_date)->format('d.m.Y') : '-' }}</td>
                                <td>₺{{ number_format($mh->cost, 2, ',', '.') }}</td>
                                <td class="text-end pe-3">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">{{ ucfirst($mh->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Kayıtlı bakım geçmişi bulunmuyor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
