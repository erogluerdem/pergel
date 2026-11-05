@extends('layouts.master')

@section('title', 'Yeni Ödeme Planı Şablonu Oluştur')

@push('styles')
<style>
    .fp-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .fp-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: fpCreateGlow 4s linear infinite;
    }

    @keyframes fpCreateGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .fp-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-fp-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-fp {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-fp:focus {
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
    <div class="fp-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill fp-badge-pill">
                        <i class="ri-bank-card-fill text-warning fs-5"></i> FİNANS & ESNEK ÖDEME PLANLARI
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Şablon Tanımı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Ödeme Planı Şablonu Oluştur</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Satış süreçlerinde ve faturalarda kullanılacak taksit ve vade kurallarını belirleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('flexible-payments.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Ödeme Planları Listesi
                </a>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-fp-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-add-circle-fill text-warning fs-5"></i>Ödeme Planı Tanımlama Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('flexible-payments.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- Plan Name -->
                    <div class="col-12">
                        <label for="name" class="form-label-custom">Şablon / Ödeme Planı Adı <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control custom-input-fp @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="Örn: 12 Aylık Standart Eşit Taksitli Konut Planı" required>
                        @error('name')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Installment Count -->
                    <div class="col-md-4">
                        <label for="installment_count" class="form-label-custom">Taksit Sayısı <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="installment_count" id="installment_count" class="form-control custom-input-fp @error('installment_count') is-invalid @enderror" 
                                   value="{{ old('installment_count', 12) }}" min="1" max="36" required>
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">Taksit</span>
                        </div>
                        @error('installment_count')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Interval Days -->
                    <div class="col-md-4">
                        <label for="interval_days" class="form-label-custom">Taksit İntervalı (Gün) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="interval_days" id="interval_days" class="form-control custom-input-fp @error('interval_days') is-invalid @enderror" 
                                   value="{{ old('interval_days', 30) }}" min="1" max="365" required>
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">Gün</span>
                        </div>
                        <small class="text-muted fw-semibold mt-1 d-block">Aylık taksitler için 30 gün giriniz.</small>
                        @error('interval_days')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Down Payment Percentage -->
                    <div class="col-md-4">
                        <label for="down_payment_percentage" class="form-label-custom">Peşinat Yüzdesi (%) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="down_payment_percentage" id="down_payment_percentage" class="form-control custom-input-fp @error('down_payment_percentage') is-invalid @enderror" 
                                   value="{{ old('down_payment_percentage', 25) }}" step="0.01" min="0" max="100" required>
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">%</span>
                        </div>
                        @error('down_payment_percentage')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Description -->
                    <div class="col-md-6">
                        <label for="description" class="form-label-custom">Şablon Açıklaması</label>
                        <textarea name="description" id="description" class="form-control custom-input-fp" rows="3" placeholder="Örn: %25 peşinat ve 12 eşit taksitle ödeme imkanı sunan standart konut ödeme şablonu.">{{ old('description') }}</textarea>
                    </div>

                    <!-- Notes -->
                    <div class="col-md-6">
                        <label for="notes" class="form-label-custom">Notlar & Özel Şartlar</label>
                        <textarea name="notes" id="notes" class="form-control custom-input-fp" rows="3" placeholder="Örn: Sıfır faiz avantajlıdır, erken ödeme indirimi mevcuttur.">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Template Switch -->
                    <div class="col-12">
                        <div class="p-3.5 rounded-3 border d-flex align-items-center gap-3" style="background: #f8fafc; border-left: 4px solid #d97706 !important;">
                            <input type="checkbox" name="is_template" id="is_template" class="form-check-input" value="1" style="width: 22px; height: 22px;" @checked(old('is_template', true))>
                            <label class="form-check-label fw-extrabold text-dark mb-0" for="is_template">
                                Genel Şablon Olarak Kaydet (Diğer modüllerde ve tüm satışlarda tekrar kullanılabilir)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('flexible-payments.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Şablonu Kaydet & Yayınla
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
