@extends('layouts.master')

@section('title', 'Yeni Satış Opsiyonu Oluştur')

@push('styles')
<style>
    .sr-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .sr-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: srCreateGlow 4s linear infinite;
    }

    @keyframes srCreateGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .sr-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-sr-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-sr {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-sr:focus {
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
    <div class="sr-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill sr-badge-pill">
                        <i class="ri-file-contract-fill text-warning fs-5"></i> CRM & GAYRİMENKUL SATIŞ OPSİYONLARI
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Opsiyon Tanımı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Satış Opsiyonu Oluştur</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Bağımsız bölüm opsiyonunu, müşteriyi, kaparo şartlarını ve opsiyon son geçerlilik tarihini tanımlayın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('sales-reservations.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Opsiyon Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-sr-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-add-circle-fill text-warning fs-5"></i>Opsiyon Tanımlama Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('sales-reservations.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- Customer -->
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label-custom">Müşteri Seçimi <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-sr @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                            <option value="">-- Müşteri Seçiniz --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    👤 {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div class="col-md-6">
                        <label for="unit_id" class="form-label-custom">Satılacak Birim / Bağımsız Bölüm <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-sr @error('unit_id') is-invalid @enderror" id="unit_id" name="unit_id" required>
                            <option value="">-- Birim Seçiniz --</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    🏢 Birim No: {{ $unit->unit_no }} ({{ $unit->unit_type }})
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Option No -->
                    <div class="col-md-6">
                        <label for="option_no" class="form-label-custom">Opsiyon No / Kodu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control custom-input-sr @error('option_no') is-invalid @enderror" id="option_no" name="option_no" 
                            placeholder="Örn: OPS-2026-009" value="{{ old('option_no', 'OPS-' . date('Y') . '-' . rand(100, 999)) }}" required>
                        @error('option_no')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label-custom">Opsiyon Durumu <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-sr @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>🟡 Beklemede (Kaparo Bekleniyor)</option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>🟢 Onaylandı (Opsiyon Kesinleşti)</option>
                            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>⚪ Süresi Doldu</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>🔴 İptal Edildi</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dates -->
                    <div class="col-md-6">
                        <label for="option_date" class="form-label-custom">Opsiyon Başlangıç Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control custom-input-sr @error('option_date') is-invalid @enderror" id="option_date" name="option_date" value="{{ old('option_date', date('Y-m-d')) }}" required>
                        @error('option_date')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="expiry_date" class="form-label-custom">Son Geçerlilik Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control custom-input-sr @error('expiry_date') is-invalid @enderror" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', date('Y-m-d', strtotime('+14 days'))) }}" required>
                        @error('expiry_date')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Option Price -->
                    <div class="col-md-6">
                        <label for="option_price" class="form-label-custom">Opsiyon Fiyatı (TL) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" class="form-control custom-input-sr @error('option_price') is-invalid @enderror" id="option_price" name="option_price" step="0.01" placeholder="0.00" value="{{ old('option_price') }}" required>
                        </div>
                        @error('option_price')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Discount Amount -->
                    <div class="col-md-6">
                        <label for="discount_amount" class="form-label-custom">İndirim Tutarı (TL)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" class="form-control custom-input-sr @error('discount_amount') is-invalid @enderror" id="discount_amount" name="discount_amount" step="0.01" placeholder="0.00" value="{{ old('discount_amount', '0') }}">
                        </div>
                        @error('discount_amount')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label for="notes" class="form-label-custom">Notlar ve Şartlar</label>
                        <textarea class="form-control custom-input-sr @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4" placeholder="Örn: 15 günlük opsiyon süresi içinde kaparo yatırılması durumunda sözleşme yapılacaktır.">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('sales-reservations.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Opsiyonu Kaydet & Yayınla
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
