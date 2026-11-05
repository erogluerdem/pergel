@extends('layouts.master')

@section('title', 'E-Fatura Detayı — ' . ($invoice->no ?? 'EFA-2026-000142'))

@section('topbar-title', 'E-Fatura Detayı')

@push('styles')
<style>
    .edoc-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #3b82f6 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .edoc-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .edoc-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $invNo = $invoice->no ?? 'EFA-2026-000142';
    $customerName = $invoice->customer->name ?? 'Mega Yapı Malzemeleri A.Ş.';
    $amount = $invoice->amount ?? 450000.00;
    $kdv = $amount * 0.20;
    $subtotal = $amount - $kdv;
@endphp

<!-- Hero Banner -->
<div class="edoc-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.edoc.efatura') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>E-Fatura Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $invNo }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">E-Fatura Önizleme & GİB Kaydı</h1>
            <p class="text-white-50 mb-0 fs-6">Alıcı: {{ $customerName }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.edoc.edit', $id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>Faturayı Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="edoc-glass-card p-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">E-FATURA</h3>
                    <small class="text-muted font-monospace">ETTKN: 9f8a42c1-3d71-4b11-9a42-019d82f718a2</small>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">
                    GİB İmzalı & Onaylı
                </span>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="fw-bold text-muted text-uppercase small mb-1">DÜZENLEYEN ŞİRKET (SATICI)</h6>
                    <strong class="text-dark d-block">Pergel İnşaat & Proje A.Ş.</strong>
                    <small class="text-muted d-block">Vergi Dairesi: Maslak V.D. / 7290192831</small>
                    <small class="text-muted d-block">Adres: Büyükdere Cad. No: 195 Levent / İstanbul</small>
                </div>
                <div class="col-md-6 text-end">
                    <h6 class="fw-bold text-muted text-uppercase small mb-1">ALICI ŞİRKET (MÜŞTERİ)</h6>
                    <strong class="text-dark d-block">{{ $customerName }}</strong>
                    <small class="text-muted d-block">Vergi Dairesi: Boğaziçi V.D. / 3829104820</small>
                    <small class="text-muted d-block">Fatura Tarihi: {{ date('d.m.Y') }}</small>
                </div>
            </div>

            <!-- Detail Lines -->
            <div class="table-responsive mb-4">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Hizmet / Malzeme Açıklaması</th>
                            <th>Miktar</th>
                            <th>Birim Fiyat</th>
                            <th class="text-end">Tutar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>A-Blok Mimari & Statik İmalat Hizmeti Hakediş Bedeli</strong></td>
                            <td>1 Adet</td>
                            <td>₺{{ number_format($subtotal, 2, ',', '.') }}</td>
                            <td class="text-end">₺{{ number_format($subtotal, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="3" class="text-end">Matrah (KDV Hariç):</td>
                            <td class="text-end">₺{{ number_format($subtotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">KDV (%20):</td>
                            <td class="text-end text-primary">₺{{ number_format($kdv, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="fs-5 text-dark">
                            <td colspan="3" class="text-end">Ödenecek Toplam Tutar:</td>
                            <td class="text-end text-success">₺{{ number_format($amount, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
