@extends('layouts.master')

@section('title', 'Yeni E-Fatura Kes')

@section('topbar-title', 'E-Fatura - Yeni Fatura')

@push('styles')
<style>
    .edoc-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #3b82f6 50%, #1d4ed8 100%);
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
                <a href="{{ route('finance.edoc.efatura') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>E-Fatura Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni E-Fatura Kes & GİB'e Gönder</h1>
            <p class="text-white-50 mb-0 fs-6">Alıcı vergi kimlik numarası, imalat kalemi ve KDV matrahlarını tanımlayın.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="edoc-form-card">
            <form action="{{ route('finance.edoc.efatura') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label fw-bold small text-dark">Alıcı Şirket / Müşteri <span class="text-danger">*</span></label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="">Müşteri seçin...</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="fatura_no" class="form-label fw-bold small text-dark">Fatura Seri / No <span class="text-danger">*</span></label>
                        <input type="text" id="fatura_no" class="form-control font-monospace fw-bold" value="EFA-2026-{{ sprintf('%06d', rand(100, 999)) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="amount" class="form-label fw-bold small text-dark">Fatura Toplam Tutarı (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="amount" class="form-control text-primary fw-bold" placeholder="0.00" required>
                    </div>

                    <div class="col-md-6">
                        <label for="fatura_tarihi" class="form-label fw-bold small text-dark">Fatura Tarihi <span class="text-danger">*</span></label>
                        <input type="date" id="fatura_tarihi" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">Fatura Notları & Banka IBAN Bilgileri</label>
                    <textarea id="notes" class="form-control" rows="4" placeholder="İş Bankası TR92 0006 4000... IBAN hesabına ödeme yapılacaktır. Hakediş #3 karşılığıdır."></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.edoc.efatura') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-send-plane-line me-1"></i>Faturayı İmzala & GİB'e Gönder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
