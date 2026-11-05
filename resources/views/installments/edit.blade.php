@extends('layouts.master')

@section('title', 'Taksit Düzenle — #' . $installment->installment_number)

@push('styles')
<style>
    .ins-edit-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .ins-edit-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: insEditGlow 4s linear infinite;
    }

    @keyframes insEditGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .ins-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-ins-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-ins {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-ins:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .form-label-custom {
        font-weight: 800 !important;
        color: #0f172a !important;
        font-size: 0.88rem !important;
        margin-bottom: 6px !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="ins-edit-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill ins-badge-pill">
                        <i class="ri-money-dollar-circle-fill text-warning fs-5"></i> FİNANS & TAKSİT TAKİBİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Taksit Revizyonu
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Taksit Düzenle (#{{ $installment->installment_number }})</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Kayıtlı taksit tutarını, son ödeme vade tarihini ve ek ceza/not bilgilerini güncelleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('installments.show', $installment->id) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-eye-line me-1"></i>Detaylara Dön
                    </a>
                    <a href="{{ route('installments.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Taksit Portföyü
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-ins-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-edit-fill text-warning fs-5"></i>Taksit Güncelleme Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('installments.update', $installment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Bağlı Fatura & Müşteri</label>
                        <input type="text" class="form-control custom-input-ins bg-light opacity-75" 
                               value="🧾 {{ $installment->invoice->no ?? 'Fatura Yok' }} — 👤 {{ $installment->invoice->customer->name ?? '' }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Taksit Sıra No</label>
                        <input type="text" class="form-control custom-input-ins font-monospace fw-extrabold bg-light opacity-75 text-warning" 
                               value="#{{ $installment->installment_number }}" readonly>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Amount -->
                    <div class="col-md-6">
                        <label for="amount" class="form-label-custom">Taksit Tutarı (₺) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" class="form-control custom-input-ins font-monospace @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" step="0.01" min="0.01" 
                                   value="{{ old('amount', $installment->amount) }}" required>
                        </div>
                        @error('amount')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Due Date -->
                    <div class="col-md-6">
                        <label for="due_date" class="form-label-custom">Son Ödeme Vade Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control custom-input-ins @error('due_date') is-invalid @enderror" 
                               id="due_date" name="due_date" 
                               value="{{ old('due_date', $installment->due_date ? $installment->due_date->format('Y-m-d') : '') }}" required>
                        @error('due_date')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Penalty Amount -->
                    <div class="col-md-6">
                        <label for="penalty_amount" class="form-label-custom">Gecikme Cezası (₺)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-danger border-0 fw-bold">₺</span>
                            <input type="number" class="form-control custom-input-ins font-monospace @error('penalty_amount') is-invalid @enderror" 
                                   id="penalty_amount" name="penalty_amount" step="0.01" min="0" 
                                   value="{{ old('penalty_amount', $installment->penalty_amount ?? 0) }}">
                        </div>
                        @error('penalty_amount')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label for="notes" class="form-label-custom">Açıklama & Notlar</label>
                        <textarea class="form-control custom-input-ins" id="notes" name="notes" rows="4" 
                                  placeholder="Taksit ile ilgili açıklama, revizyon ve banka notları...">{{ old('notes', $installment->notes) }}</textarea>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('installments.show', $installment->id) }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Değişiklikleri Kaydet & Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
