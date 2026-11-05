@extends('layouts.master')

@section('title', 'Ödeme Planı Düzenle — ' . $paymentPlan->plan_no)

@push('styles')
<style>
    .pp-edit-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .pp-edit-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: ppEditGlow 4s linear infinite;
    }

    @keyframes ppEditGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .pp-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-pp-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-pp {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-pp:focus {
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
    <div class="pp-edit-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill pp-badge-pill">
                        <i class="ri-calendar-todo-fill text-warning fs-5"></i> FİNANS & TAKSİT TAKVİMİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Ödeme Planı Revizyonu
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Ödeme Planı Düzenle — {{ $paymentPlan->plan_no }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Kayıtlı taksitlendirme planının tutarını, vade tarihlerini, taksit periyodunu ve durumunu güncelleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('payment-plans.show', $paymentPlan->id) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-eye-line me-1"></i>Detaylara Dön
                    </a>
                    <a href="{{ route('payment-plans.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Planlar Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-pp-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-edit-box-fill text-warning fs-5"></i>Ödeme Planı Güncelleme Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('payment-plans.update', $paymentPlan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Invoice Select -->
                    <div class="col-md-6">
                        <label for="invoice_id" class="form-label-custom">İlişkili Fatura <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-pp @error('invoice_id') is-invalid @enderror" id="invoice_id" name="invoice_id" required>
                            <option value="">-- Fatura Seçiniz --</option>
                            @foreach ($invoices as $invoice)
                                <option value="{{ $invoice->id }}" {{ old('invoice_id', $paymentPlan->invoice_id) == $invoice->id ? 'selected' : '' }}>
                                    🧾 {{ $invoice->no }} — 👤 {{ $invoice->customer->name ?? 'Müşteri' }} (₺{{ number_format($invoice->amount, 2, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_id')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Plan No -->
                    <div class="col-md-6">
                        <label for="plan_no" class="form-label-custom">Ödeme Plan Kodu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control custom-input-pp font-monospace @error('plan_no') is-invalid @enderror" id="plan_no" name="plan_no" 
                               placeholder="Örn: PLN-2025-005" value="{{ old('plan_no', $paymentPlan->plan_no) }}" required>
                        @error('plan_no')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Total Amount -->
                    <div class="col-md-6">
                        <label for="total_amount" class="form-label-custom">Vadelendirilecek Toplam Tutar (₺) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" class="form-control custom-input-pp font-monospace @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" 
                                   step="0.01" min="0" placeholder="Örn: 500000" value="{{ old('total_amount', $paymentPlan->total_amount) }}" required>
                        </div>
                        @error('total_amount')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Installment Count -->
                    <div class="col-md-6">
                        <label for="installment_count" class="form-label-custom">Taksit Sayısı <span class="text-danger">*</span></label>
                        <input type="number" class="form-control custom-input-pp font-monospace @error('installment_count') is-invalid @enderror" id="installment_count" name="installment_count" 
                               min="1" max="36" placeholder="Örn: 12" value="{{ old('installment_count', $paymentPlan->installment_count) }}" required>
                        @error('installment_count')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Start Date -->
                    <div class="col-md-4">
                        <label for="start_date" class="form-label-custom">İlk Taksit Vade Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control custom-input-pp @error('start_date') is-invalid @enderror" id="start_date" name="start_date" 
                               value="{{ old('start_date', $paymentPlan->start_date ? $paymentPlan->start_date->format('Y-m-d') : '') }}" required>
                        @error('start_date')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Interval Days -->
                    <div class="col-md-4">
                        <label for="payment_interval_days" class="form-label-custom">Taksit Periyodu (Gün) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control custom-input-pp font-monospace @error('payment_interval_days') is-invalid @enderror" id="payment_interval_days" name="payment_interval_days" 
                               min="1" max="365" placeholder="30" value="{{ old('payment_interval_days', $paymentPlan->payment_interval_days) }}" required>
                        @error('payment_interval_days')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label for="status" class="form-label-custom">Plan Durumu <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-pp @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status', $paymentPlan->status) == 'active' ? 'selected' : '' }}>✓ Aktif Plan</option>
                            <option value="completed" {{ old('status', $paymentPlan->status) == 'completed' ? 'selected' : '' }}>🎉 Tamamlandı</option>
                            <option value="cancelled" {{ old('status', $paymentPlan->status) == 'cancelled' ? 'selected' : '' }}>❌ İptal</option>
                        </select>
                        @error('status')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label for="notes" class="form-label-custom">Açıklama & Banka Ödeme Notları</label>
                        <textarea class="form-control custom-input-pp" id="notes" name="notes" rows="4" 
                                  placeholder="Ödeme takvimine ilişkin banka IBAN bilgileri, opsiyonel detaylar ve taksit şerhleri...">{{ old('notes', $paymentPlan->notes) }}</textarea>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('payment-plans.show', $paymentPlan->id) }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
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
