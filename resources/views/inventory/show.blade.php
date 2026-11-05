@extends('layouts.master')

@section('title', 'Envanter Öğesi Detayı — ' . ($item->name ?? 'Öğe'))
@section('topbar-title', 'Envanter - Detay')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="fw-bold mb-1">
                <i class="ri-box-3-line text-primary me-2"></i>{{ $item->name }}
            </h4>
            <p class="text-muted mb-0">Envanter Kodu: <strong>{{ $item->sku ?? $item->code ?? ('#' . $item->id) }}</strong> | Kategori: {{ $item->category ?? '-' }}</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-warning text-dark">
                <i class="ri-pencil-line me-1"></i>Düzenle
            </a>
            <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary ms-1">
                <i class="ri-arrow-left-line me-1"></i>Envanter Listesi
            </a>
        </div>
    </div>

    @php
        $unitPrice = $item->price ?? $item->unit_price ?? 0;
        $totalVal = method_exists($item, 'totalValue') ? $item->totalValue() : (($item->quantity ?? 0) * $unitPrice);
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-start border-primary border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Mevcut Miktar</small>
                    <h3 class="fw-bold mt-1 mb-0">{{ $item->quantity ?? 0 }} {{ $item->unit ?? 'Adet' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-info border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Birim Fiyat</small>
                    <h3 class="fw-bold mt-1 mb-0">₺{{ number_format($unitPrice, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-success border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Toplam Envanter Değeri</small>
                    <h3 class="fw-bold text-success mt-1 mb-0">₺{{ number_format($totalVal, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-warning border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Konum / Depo</small>
                    <h5 class="fw-bold mt-2 mb-0">{{ $item->location ?? '-' }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Öğe Bilgileri</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p class="mb-1 text-muted small">Tedarikçi</p>
                    <p class="fw-semibold">{{ $item->supplier ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted small">Durum</p>
                    <p class="fw-semibold"><span class="badge bg-primary">{{ $item->status ?? 'Aktif' }}</span></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted small">Minimum Kritik Stok</p>
                    <p class="fw-semibold">{{ $item->min_quantity ?? 0 }} {{ $item->unit ?? 'Adet' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted small">Son Güncelleme</p>
                    <p class="fw-semibold">{{ $item->updated_at ? $item->updated_at->format('d.m.Y H:i') : '-' }}</p>
                </div>
                <div class="col-12">
                    <p class="mb-1 text-muted small">Açıklama</p>
                    <p class="mb-0">{{ $item->description ?? 'Açıklama girilmemiş.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
