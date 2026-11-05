@extends('layouts.master')

@section('title', 'Tedarikçi Fiyat Teklifi Detayı')

@section('topbar-title', 'Satın Alma - Teklif Detayı')

@push('styles')
<style>
    .purchasing-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .purchasing-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .purchasing-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $quoteNum = $quotation->quote_number ?: 'QT-2026-000101';
    $vendorName = $quotation->vendor->name ?? 'Tedarikçi Firma';
    $amount = $quotation->total_amount ?? 1250000.00;
@endphp

<!-- Hero Banner -->
<div class="purchasing-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('purchasing.quotations.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Teklifler Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $quoteNum }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Tedarikçi Teklif & Birim Fiyat Analizi</h1>
            <p class="text-white-50 mb-0 fs-6">Tedarikçi: {{ $vendorName }} — İletişim: {{ $quotation->vendor->phone ?? '0532 111 2233' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('purchasing.quotations.edit', $id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>Teklifi Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="purchasing-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $vendorName }} — Fiyat Teklifi</h3>
                    <small class="text-muted">Teklif Tarihi: {{ $quotation->quote_date ? $quotation->quote_date->format('d.m.Y') : date('d.m.Y') }} | Son Geçerlilik: {{ $quotation->valid_until ? $quotation->valid_until->format('d.m.Y') : '-' }}</small>
                </div>
                <div>
                    @if($quotation->status === 'approved')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Onaylandı</span>
                    @elseif($quotation->status === 'rejected')
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Reddedildi</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Değerlendirmede</span>
                    @endif
                </div>
            </div>

            <!-- Summary Specs -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Toplam Teklif Tutarı</span>
                    <h3 class="fw-extrabold mb-0 text-primary">₺{{ number_format($amount, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Tedarikçi Uzmanlık Alanı</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $quotation->vendor->specialty ?? 'Malzeme Tedariki' }}</h4>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Tedarikçi Performans Skoru</span>
                    <h4 class="fw-bold mb-0 text-success">%{{ $quotation->vendor->performance_score ?? 95 }} Başarı</h4>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Birim Fiyat & Malzeme Kalemleri Tablosu</h6>
            <div class="table-responsive border rounded-3 mb-4">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Malzeme / Hizmet Açıklaması</th>
                            <th>Miktar</th>
                            <th class="text-end">Birim Fiyat (₺)</th>
                            <th class="text-end pe-3">Toplam Tutar (₺)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quotation->items as $item)
                            <tr>
                                <td class="ps-3 fw-bold text-dark">{{ $item->description }}</td>
                                <td>{{ number_format($item->quantity, 0, ',', '.') }}</td>
                                <td class="text-end">₺{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                <td class="text-end pe-3 fw-bold text-dark">₺{{ number_format($item->total_price, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Teklif detay kalemi bulunamadı.</td>
                            </tr>
                        @endforelse
                        <tr class="table-light fw-bold fs-6">
                            <td colspan="3" class="text-end">GENEL TEKLİF TOPLAMI</td>
                            <td class="text-end pe-3 text-primary fs-5">₺{{ number_format($amount, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="fw-bold text-dark mb-2">Tedarikçi Teklif Notları & Vade Koşulları</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark" style="white-space: pre-line;">
                {{ $quotation->notes ?: 'Tedarikçi tarafından özel bir not girilmemiş.' }}
            </div>
        </div>
    </div>
</div>

@endsection
