@extends('layouts.master')

@section('title', 'Yeni Taksit Kaydı Oluştur')

@push('styles')
<style>
    .ins-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .ins-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: insCreateGlow 4s linear infinite;
    }

    @keyframes insCreateGlow {
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
    <div class="ins-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill ins-badge-pill">
                        <i class="ri-money-dollar-circle-fill text-warning fs-5"></i> FİNANS & TAKSİT TAKİBİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Taksit Kaydı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Taksit Kaydı Oluştur</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Kayıtlı bir ödeme planına bağlı tekil taksit ve vade tarihi ekleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('installments.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Taksit Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-ins-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-calendar-todo-line text-warning fs-5"></i>Taksit Kayıt Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('installments.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- Payment Plan Select -->
                    <div class="col-md-6">
                        <label for="payment_plan_id" class="form-label-custom">İlişkili Ödeme Planı <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-ins @error('payment_plan_id') is-invalid @enderror" id="payment_plan_id" name="payment_plan_id" required>
                            <option value="">-- Ödeme Planı Seçiniz --</option>
                            @foreach($paymentPlans as $plan)
                                <option value="{{ $plan->id }}" {{ (old('payment_plan_id') == $plan->id || (isset($paymentPlan) && $paymentPlan->id == $plan->id)) ? 'selected' : '' }}>
                                    📜 {{ $plan->plan_no }} — {{ $plan->name ?? 'Ödeme Planı' }} (Top: ₺{{ number_format($plan->total_amount, 2, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('payment_plan_id')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Installment Number -->
                    <div class="col-md-6">
                        <label for="installment_number" class="form-label-custom">Taksit Sıra No <span class="text-danger">*</span></label>
                        <input type="number" class="form-control custom-input-ins font-monospace @error('installment_number') is-invalid @enderror" 
                               id="installment_number" name="installment_number" min="1" max="120" 
                               placeholder="Örn: 1" value="{{ old('installment_number', '1') }}" required>
                        @error('installment_number')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Amount -->
                    <div class="col-md-6">
                        <label for="amount" class="form-label-custom">Taksit Tutarı (₺) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" class="form-control custom-input-ins font-monospace @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" step="0.01" min="0.01" 
                                   placeholder="Örn: 25000" value="{{ old('amount') }}" required>
                        </div>
                        @error('amount')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Due Date -->
                    <div class="col-md-6">
                        <label for="due_date" class="form-label-custom">Son Ödeme Vade Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control custom-input-ins @error('due_date') is-invalid @enderror" 
                               id="due_date" name="due_date" value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}" required>
                        @error('due_date')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label for="notes" class="form-label-custom">Notlar & Özel Şartlar</label>
                        <textarea class="form-control custom-input-ins" id="notes" name="notes" rows="4" 
                                  placeholder="Taksit ödemesine ilişkin banka detayları, opsiyonel açıklamalar ve taksit şerhleri...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('installments.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Taksiti Kaydet & Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
