@extends('layouts.master')

@section('title', 'Yeni Dijital Sözleşme Oluştur')

@push('styles')
<style>
    .dc-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .dc-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: dcCreateGlow 4s linear infinite;
    }

    @keyframes dcCreateGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .dc-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-dc-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-dc {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-dc:focus {
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
    <div class="dc-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill dc-badge-pill">
                        <i class="ri-shield-keyhole-line text-warning fs-5"></i> HUKUK & İDARİ - DİJİTAL SÖZLEŞMELER
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Sözleşme Tanımı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Dijital Sözleşme Oluştur</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Sözleşme şartnamesini, ilişkili projeyi, taraf şirket unvanını, bedeli ve yürürlük tarihlerini tanımlayın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('digital-contracts.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Sözleşmeler Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-dc-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-file-add-fill text-warning fs-5"></i>Dijital Sözleşme Kayıt Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('digital-contracts.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <!-- Project -->
                    <div class="col-md-6">
                        <label for="project_id" class="form-label-custom">İlişkili Proje <span class="text-danger">*</span></label>
                        <select name="project_id" id="project_id" class="form-select custom-input-dc @error('project_id') is-invalid @enderror" required>
                            <option value="">-- Proje Seçiniz --</option>
                            @foreach($projects as $id => $name)
                                <option value="{{ $id }}" @selected(old('project_id') == $id)>🏗️ {{ $name }}</option>
                            @endforeach
                        </select>
                        @error('project_id')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Title -->
                    <div class="col-md-6">
                        <label for="title" class="form-label-custom">Sözleşme Başlığı <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control custom-input-dc @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" placeholder="Örn: Mavi Kule 101 Ön Satış Vade Sözleşmesi" required>
                        @error('title')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Company -->
                    <div class="col-md-6">
                        <label for="company" class="form-label-custom">Şirket / Taraf Firma Unvanı</label>
                        <input type="text" name="company" id="company" class="form-control custom-input-dc" 
                               value="{{ old('company') }}" placeholder="Örn: Pergel İnşaat Gayrimenkul A.Ş.">
                    </div>

                    <!-- Value -->
                    <div class="col-md-6">
                        <label for="value" class="form-label-custom">Sözleşme Tutarı (₺)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" step="0.01" name="value" id="value" class="form-control custom-input-dc" 
                                   value="{{ old('value') }}" placeholder="12500000">
                        </div>
                    </div>

                    <!-- Effective Date -->
                    <div class="col-md-4">
                        <label for="effective_date" class="form-label-custom">Yürürlük Başlangıç Tarihi <span class="text-danger">*</span></label>
                        <input type="date" name="effective_date" id="effective_date" class="form-control custom-input-dc @error('effective_date') is-invalid @enderror" 
                               value="{{ old('effective_date', date('Y-m-d')) }}" required>
                        @error('effective_date')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Expiration Date -->
                    <div class="col-md-4">
                        <label for="expiration_date" class="form-label-custom">Bitiş / Fesih Tarihi</label>
                        <input type="date" name="expiration_date" id="expiration_date" class="form-control custom-input-dc" 
                               value="{{ old('expiration_date') }}">
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label for="status" class="form-label-custom">Sözleşme Durumu <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select custom-input-dc @error('status') is-invalid @enderror" required>
                            <option value="active" @selected(old('status', 'active') == 'active')>🟢 Aktif / Yürürlükte</option>
                            <option value="draft" @selected(old('status') == 'draft')>🟡 Taslak / Onay Bekliyor</option>
                            <option value="expired" @selected(old('status') == 'expired')>⚪ Süresi Dolmuş</option>
                            <option value="cancelled" @selected(old('status') == 'cancelled')>🔴 İptal Edildi</option>
                        </select>
                        @error('status')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Content -->
                    <div class="col-12">
                        <label for="content" class="form-label-custom">Sözleşme İçeriği ve Şartname Metni <span class="text-danger">*</span></label>
                        <textarea name="content" id="content" class="form-control custom-input-dc @error('content') is-invalid @enderror" rows="8" placeholder="Sözleşme maddelerini, tarafları, cezai şartları ve e-imza hükümlerini buraya yazınız..." required>{{ old('content') }}</textarea>
                        @error('content')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('digital-contracts.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Sözleşmeyi Kaydet & Yayınla
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
