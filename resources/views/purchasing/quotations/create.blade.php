@extends('layouts.master')

@section('title', 'Yeni Tedarikçi Teklifi Girişi')

@section('topbar-title', 'Satın Alma - Yeni Teklif')

@push('styles')
<style>
    .purchasing-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .purchasing-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .purchasing-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="purchasing-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('purchasing.quotations.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Teklifler Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Tedarikçi Fiyat Teklifi Girişi</h1>
            <p class="text-white-50 mb-0 fs-6">İhale ve satın alma kalemleri için tedarikçi birim fiyat teklifini sisteme kaydettirin.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="purchasing-form-card">
            <form action="{{ route('purchasing.quotations.index') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="vendor_id" class="form-label fw-bold small text-dark">Tedarikçi Firma <span class="text-danger">*</span></label>
                        <select name="vendor_id" id="vendor_id" class="form-select" required>
                            <option value="">Tedarikçi seçin...</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id }}">{{ $v->name }} - {{ $v->specialty ?? 'Malzeme Tedariki' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="quote_number" class="form-label fw-bold small text-dark">Teklif Referans No <span class="text-danger">*</span></label>
                        <input type="text" id="quote_number" class="form-control font-monospace fw-bold" value="QT-2026-{{ rand(1000, 9999) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="quote_date" class="form-label fw-bold small text-dark">Teklif Tarihi <span class="text-danger">*</span></label>
                        <input type="date" id="quote_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="valid_until" class="form-label fw-bold small text-dark">Son Geçerlilik Tarihi <span class="text-danger">*</span></label>
                        <input type="date" id="valid_until" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="total_amount" class="form-label fw-bold small text-dark">Toplam Teklif Tutarı (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="total_amount" class="form-control text-primary fw-bold" placeholder="0.00" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">Tedarikçi Özel Koşulları & Vade Notları</label>
                    <textarea id="notes" class="form-control" rows="4" placeholder="Şantiye teslim şartları, ödeme vadesi ve teslim süreleri..."></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('purchasing.quotations.index') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-primary text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#2563eb;">
                        <i class="ri-save-line me-1"></i>Teklifi Kaydet & Değerlendirmeye Al
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
