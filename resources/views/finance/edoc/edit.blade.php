@extends('layouts.master')

@section('title', 'E-Faturayı Düzenle')

@section('topbar-title', 'E-Fatura - Düzenle')

@push('styles')
<style>
    .edoc-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .edoc-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .edoc-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="edoc-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.edoc.show', $id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Fatura Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">E-Fatura Taslağını Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">GİB'e gönderilmeden önceki taslak fatura detaylarını güncelleyin.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="edoc-form-card">
            <form action="{{ route('finance.edoc.efatura') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="amount" class="form-label fw-bold small text-dark">Fatura Tutarı (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="amount" class="form-control text-primary fw-bold" value="{{ old('amount', $invoice->amount ?? 450000.00) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="fatura_tarihi" class="form-label fw-bold small text-dark">Fatura Tarihi <span class="text-danger">*</span></label>
                        <input type="date" id="fatura_tarihi" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">Fatura Notları & Açıklamalar</label>
                    <textarea id="notes" class="form-control" rows="4">{{ old('notes', $invoice->notes ?? 'İş Bankası TR92 0006 4000... IBAN hesabına ödeme yapılacaktır.') }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.edoc.show', $id) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
