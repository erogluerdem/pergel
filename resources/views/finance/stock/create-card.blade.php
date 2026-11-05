@extends('layouts.master')

@section('title', 'Yeni Stok Kartı Oluştur')

@section('topbar-title', 'Finans - Yeni Stok Kartı')

@push('styles')
<style>
    .stock-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0d9488 50%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .stock-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .stock-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="stock-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.stock.cards') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Stok Kartları Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Stok Kartı Oluştur</h1>
            <p class="text-white-50 mb-0 fs-6">İnşaat malzemesi, tesisat, elektrik ve sarf malzeme stok kartı tanımlama formu.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="stock-form-card">
            <form action="{{ route('finance.stock.cards') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label fw-bold small text-dark">Stok Kodu <span class="text-danger">*</span></label>
                        <input type="text" id="code" class="form-control font-monospace fw-bold" value="STK-0010{{ rand(6, 9) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold small text-dark">Malzeme Adı & Tanımı <span class="text-danger">*</span></label>
                        <input type="text" id="name" class="form-control" placeholder="Örn: C35/40 Hazır Beton" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="category" class="form-label fw-bold small text-dark">Kategori <span class="text-danger">*</span></label>
                        <select id="category" class="form-select" required>
                            <option value="Inşaat Malzemesi">İnşaat Malzemesi</option>
                            <option value="Demir & Çelik">Demir & Çelik</option>
                            <option value="Tesisat & Boru">Tesisat & Boru</option>
                            <option value="Yalıtım & İzolasyon">Yalıtım & İzolasyon</option>
                            <option value="Elektrik & Aydınlatma">Elektrik & Aydınlatma</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="unit" class="form-label fw-bold small text-dark">Birim <span class="text-danger">*</span></label>
                        <input type="text" id="unit" class="form-control" placeholder="Torba, Ton, m², Metre, Adet" required>
                    </div>

                    <div class="col-md-4">
                        <label for="current_stock" class="form-label fw-bold small text-dark">Mevcut Stok Miktarı <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="current_stock" class="form-control fw-bold" placeholder="0.00" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="min_stock" class="form-label fw-bold small text-dark">Min Stok Limiti <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="min_stock" class="form-control" placeholder="10.00" required>
                    </div>

                    <div class="col-md-4">
                        <label for="max_stock" class="form-label fw-bold small text-dark">Max Stok Limiti <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="max_stock" class="form-control" placeholder="1000.00" required>
                    </div>

                    <div class="col-md-4">
                        <label for="unit_price" class="form-label fw-bold small text-dark">Birim Fiyat (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="unit_price" class="form-control text-primary fw-bold" placeholder="0.00" required>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="location" class="form-label fw-bold small text-dark">Depo / Ambar Konumu</label>
                        <input type="text" id="location" class="form-control" placeholder="Örn: Şantiye A Deposu - Raf 04">
                    </div>

                    <div class="col-md-6">
                        <label for="supplier" class="form-label fw-bold small text-dark">Tedarikçi Firma</label>
                        <input type="text" id="supplier" class="form-control" placeholder="Örn: Akçansa Çimento A.Ş.">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold small text-dark">Malzeme Detay Açıklaması & Saklama Koşulları</label>
                    <textarea id="description" class="form-control" rows="3" placeholder="Saha kullanım alanları ve depolama şartları..."></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.stock.cards') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-teal text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#0d9488;">
                        <i class="ri-save-line me-1"></i>Stok Kartını Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
