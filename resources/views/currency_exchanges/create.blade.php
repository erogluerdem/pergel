@extends('layouts.master')

@section('title', 'Yeni Döviz Alım/Satım İşlemi Ekle')

@section('topbar-title', 'Döviz İşlemleri - Yeni İşlem')

@push('styles')
<style>
    .currency-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0369a1 50%, #0284c7 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .currency-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .currency-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="currency-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('currency_exchanges.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Döviz Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Döviz Alım/Satım İşlemi Kaydet</h1>
            <p class="text-white-50 mb-0 fs-6">Banka ve kasa hesaplarınız arası döviz çevrim kurunu ve tutarlarını tanımlayın.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="currency-form-card">
            <form action="{{ route('currency_exchanges.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="from_currency" class="form-label fw-bold small text-dark">Kaynak Para Birimi (Çıkan) <span class="text-danger">*</span></label>
                        <select name="from_currency" id="from_currency" class="form-select" required>
                            <option value="">Para Birimi seçin...</option>
                            @foreach($currencies as $id => $code)
                                <option value="{{ $id }}">{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="to_currency" class="form-label fw-bold small text-dark">Hedef Para Birimi (Giren) <span class="text-danger">*</span></label>
                        <select name="to_currency" id="to_currency" class="form-select" required>
                            <option value="">Para Birimi seçin...</option>
                            @foreach($currencies as $id => $code)
                                <option value="{{ $id }}">{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="from_amount" class="form-label fw-bold small text-dark">Çıkan Tutar <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="from_amount" id="from_amount" class="form-control text-danger fw-bold" placeholder="1000.00" required>
                    </div>

                    <div class="col-md-6">
                        <label for="exchange_rate" class="form-label fw-bold small text-dark">İşlem Kuru <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="exchange_rate" id="exchange_rate" class="form-control font-monospace fw-bold" placeholder="36.4500" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="commission_percentage" class="form-label fw-bold small text-dark">Banka Komisyonu (%)</label>
                        <input type="number" step="0.01" name="commission_percentage" id="commission_percentage" class="form-control" placeholder="0.25" value="0.20">
                    </div>

                    <div class="col-md-4">
                        <label for="exchange_date" class="form-label fw-bold small text-dark">İşlem Tarihi <span class="text-danger">*</span></label>
                        <input type="date" name="exchange_date" id="exchange_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="invoice_id" class="form-label fw-bold small text-dark">İlişkili Fatura (Opsiyonel)</label>
                        <select name="invoice_id" id="invoice_id" class="form-select">
                            <option value="">Fatura seçin...</option>
                            @foreach($invoices as $id => $num)
                                <option value="{{ $id }}">Fatura #{{ $num }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">İşlem Notları & Açıklama</label>
                    <textarea name="notes" id="notes" class="form-control" rows="4" placeholder="Banka dekont no, döviz bozumu gerekçesi veya taşeron hakediş ödemesi referansı..."></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('currency_exchanges.index') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-sky text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#0284c7;">
                        <i class="ri-save-line me-1"></i>İşlemi Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
