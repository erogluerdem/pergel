@extends('layouts.master')

@section('title', 'Yeni Gayrimenkul Opsiyonu Oluştur')

@push('styles')
<style>
    .opt-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .opt-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: optCreateGlow 4s linear infinite;
    }

    @keyframes optCreateGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .opt-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-opt-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-opt {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-opt:focus {
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
    <div class="opt-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill opt-badge-pill">
                        <i class="ri-bookmark-3-fill text-warning fs-5"></i> CRM & GAYRİMENKUL OPSİYON YÖNETİMİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Opsiyon Tanımı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Gayrimenkul Opsiyonu Oluştur</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Gayrimenkul seçimi, opsiyon sahibi müşteri tanımı ve opsiyon süresini belirleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('options.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Opsiyon Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-opt-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-add-circle-fill text-warning fs-5"></i>Gayrimenkul Opsiyon Tanımlama Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('options.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- Asset -->
                    <div class="col-md-6">
                        <label for="asset_id" class="form-label-custom">Gayrimenkul / Portföy Varlığı <span class="text-danger">*</span></label>
                        <select name="asset_id" id="asset_id" class="form-select custom-input-opt @error('asset_id') is-invalid @enderror" required>
                            <option value="">-- Gayrimenkul Seçiniz --</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                    🏢 {{ $asset->name }} {{ $asset->location ? ' (' . $asset->location . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('asset_id')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Customer -->
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label-custom">Opsiyon Sahibi Müşteri <span class="text-danger">*</span></label>
                        <select name="customer_id" id="customer_id" class="form-select custom-input-opt @error('customer_id') is-invalid @enderror" required>
                            <option value="">-- Müşteri Seçiniz --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    👤 {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Duration Days -->
                    <div class="col-md-6">
                        <label for="duration_days" class="form-label-custom">Opsiyon Süresi (Gün) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="duration_days" id="duration_days" class="form-control custom-input-opt @error('duration_days') is-invalid @enderror" 
                                value="{{ old('duration_days', 14) }}" min="1" max="365" required>
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">Gün</span>
                        </div>
                        <small class="text-muted fw-semibold mt-1 d-block">Önerilen varsayılan opsiyon süresi 14 gündür.</small>
                        @error('duration_days')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Opportunity -->
                    <div class="col-md-6">
                        <label for="opportunity_id" class="form-label-custom">İlgili Satış Fırsatı (Opsiyonel)</label>
                        <select name="opportunity_id" id="opportunity_id" class="form-select custom-input-opt">
                            <option value="">-- Fırsat Eşleştirme Yapılmasın --</option>
                            @foreach($opportunities as $opp)
                                <option value="{{ $opp->id }}" {{ old('opportunity_id') == $opp->id ? 'selected' : '' }}>
                                    🎯 {{ $opp->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <label for="notes" class="form-label-custom">Özel Notlar ve Şartlar</label>
                        <textarea name="notes" id="notes" class="form-control custom-input-opt @error('notes') is-invalid @enderror" 
                            rows="4" placeholder="Örn: Müşteri %15 kaparo ödemesi yapacaktır, banka kredi onay süreci takip edilmektedir.">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- INFO ALERT BOX -->
                <div class="p-3.5 rounded-3 border mt-4" style="background: #f8fafc; border-left: 4px solid #d97706 !important; color: #0f172a;">
                    <div class="d-flex align-items-center gap-2 mb-2 fw-extrabold text-dark">
                        <i class="ri-information-fill text-warning fs-5"></i>Opsiyon Otomasyon Bilgisi
                    </div>
                    <ul class="mb-0 text-muted fw-semibold small ps-3">
                        <li>Opsiyon başlama tarihi otomatik olarak günümüz tarihi olarak kaydedilir.</li>
                        <li>Bitme tarihi = Bugün + Belirtilen Gün Sayısı olarak hesaplanır.</li>
                        <li>Opsiyon süresi dolduğunda sistem otomatik uyarır ve portföy durumunu günceller.</li>
                    </ul>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('options.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
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
