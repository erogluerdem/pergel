@extends('layouts.master')

@section('title', 'Yeni Arsa Oluştur')

@push('styles')
<style>
    .prop-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .prop-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: propCreateGlow 4s linear infinite;
    }

    @keyframes propCreateGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .prop-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-prop-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .custom-input-prop {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-prop:focus {
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
    <div class="prop-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill prop-badge-pill">
                        <i class="ri-map-pin-2-fill text-warning fs-5"></i> OPERASYON & ARSA EMLAK PORTALI
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Arsa Tanımı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Arsa & Emlak Kaydı Oluştur</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Şirket portföyüne yeni parsel/arsa ekleyin, mimari alan ölçümlerini, tapu yasal durumlarını ve ekspertiz değerini tanımlayın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('operations.properties.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Arsa Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- FORM START -->
    <form action="{{ route('operations.properties.store') }}" method="POST" class="needs-validation">
        @csrf

        <!-- SECTION 1: TEMEL BİLGİLER -->
        <div class="card custom-prop-card">
            <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-information-fill text-warning fs-5"></i>1. Temel Arsa & Proje Bilgileri
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label-custom">Bağlı Proje <span class="text-danger">*</span></label>
                        <select name="project_id" class="form-select custom-input-prop @error('project_id') is-invalid @enderror" required>
                            <option value="">-- Proje Seçiniz --</option>
                            @foreach ($projects ?? [] as $projectId => $projectName)
                                <option value="{{ $projectId }}" {{ old('project_id') == $projectId ? 'selected' : '' }}>
                                    🏗️ {{ $projectName }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Arsa Kodu <span class="text-danger">*</span></label>
                        <input type="text" name="property_code" class="form-control custom-input-prop @error('property_code') is-invalid @enderror" placeholder="Örn: ARS-2026-001" value="{{ old('property_code', 'ARS-' . date('Y') . '-' . rand(100, 999)) }}" required>
                        @error('property_code') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Arsa / Parsel Adı <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control custom-input-prop @error('name') is-invalid @enderror" placeholder="Örn: Kadıköy Sahil Ticari Arsa" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Arsa Genel Açıklaması</label>
                        <textarea name="description" class="form-control custom-input-prop @error('description') is-invalid @enderror" rows="3" placeholder="Arsanın genel vizyonu, cephe ve ulaşım avantajları...">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: LOKASYON BİLGİLERİ -->
        <div class="card custom-prop-card">
            <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-map-pin-2-line text-warning fs-5"></i>2. Lokasyon & İmar Konumu
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-3">
                        <label class="form-label-custom">Şehir <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control custom-input-prop @error('city') is-invalid @enderror" placeholder="Örn: İstanbul" value="{{ old('city') }}" required>
                        @error('city') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">İlçe</label>
                        <input type="text" name="district" class="form-control custom-input-prop @error('district') is-invalid @enderror" placeholder="Örn: Kadıköy" value="{{ old('district') }}">
                        @error('district') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">Mahalle</label>
                        <input type="text" name="neighborhood" class="form-control custom-input-prop @error('neighborhood') is-invalid @enderror" placeholder="Örn: Moda" value="{{ old('neighborhood') }}">
                        @error('neighborhood') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">İmar Durumu</label>
                        <input type="text" name="zoning_status" class="form-control custom-input-prop @error('zoning_status') is-invalid @enderror" placeholder="Örn: Konut + Ticari İmarlı" value="{{ old('zoning_status') }}">
                        @error('zoning_status') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label-custom">Tam Açık Adres <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control custom-input-prop @error('address') is-invalid @enderror" placeholder="Tam sokak, cadde ve kapı no adresi" value="{{ old('address') }}" required>
                        @error('address') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label-custom">Enlem (Latitude)</label>
                        <input type="number" name="latitude" step="0.000001" class="form-control custom-input-prop @error('latitude') is-invalid @enderror" placeholder="41.0082" value="{{ old('latitude') }}">
                        @error('latitude') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label-custom">Boylam (Longitude)</label>
                        <input type="number" name="longitude" step="0.000001" class="form-control custom-input-prop @error('longitude') is-invalid @enderror" placeholder="28.9784" value="{{ old('longitude') }}">
                        @error('longitude') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: ARSA ÖZELLİKLERİ -->
        <div class="card custom-prop-card">
            <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-ruler-2-line text-warning fs-5"></i>3. Arsa Mimari Ölçümleri (TAKS / KAKS)
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-3">
                        <label class="form-label-custom">Arsa Alanı (m²)</label>
                        <div class="input-group">
                            <input type="number" name="area" step="0.01" class="form-control custom-input-prop @error('area') is-invalid @enderror" placeholder="0.00" value="{{ old('area') }}">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">m²</span>
                        </div>
                        @error('area') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">İnşaat Alanı (m²)</label>
                        <div class="input-group">
                            <input type="number" name="building_area" step="0.01" class="form-control custom-input-prop @error('building_area') is-invalid @enderror" placeholder="0.00" value="{{ old('building_area') }}">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">m²</span>
                        </div>
                        @error('building_area') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">TAKS (Taban Alanı Oranı)</label>
                        <input type="number" name="floor_ratio" step="0.01" class="form-control custom-input-prop @error('floor_ratio') is-invalid @enderror" placeholder="Örn: 0.35" value="{{ old('floor_ratio') }}">
                        @error('floor_ratio') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">KAKS / EMSAL (Yapı Oranı)</label>
                        <input type="number" name="coverage_ratio" step="0.01" class="form-control custom-input-prop @error('coverage_ratio') is-invalid @enderror" placeholder="Örn: 2.00" value="{{ old('coverage_ratio') }}">
                        @error('coverage_ratio') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 4: TAPU & YASAL BİLGİLER -->
        <div class="card custom-prop-card">
            <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-file-paper-2-line text-warning fs-5"></i>4. Tapu Sicil & Yasal Hukuki Bilgiler
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-3">
                        <label class="form-label-custom">Tapu Kütük No</label>
                        <input type="text" name="deed_number" class="form-control custom-input-prop @error('deed_number') is-invalid @enderror" placeholder="Tapu Kütük No" value="{{ old('deed_number') }}">
                        @error('deed_number') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">Pafta / Ada No</label>
                        <input type="text" name="block_number" class="form-control custom-input-prop @error('block_number') is-invalid @enderror" placeholder="Ada No" value="{{ old('block_number') }}">
                        @error('block_number') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">Parsel No</label>
                        <input type="text" name="parcel_number" class="form-control custom-input-prop @error('parcel_number') is-invalid @enderror" placeholder="Parsel No" value="{{ old('parcel_number') }}">
                        @error('parcel_number') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-custom">Tapu Edinim Tarihi</label>
                        <input type="date" name="deed_date" class="form-control custom-input-prop @error('deed_date') is-invalid @enderror" value="{{ old('deed_date') }}">
                        @error('deed_date') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Mülk Sahibi / Şirket Adı</label>
                        <input type="text" name="owner_name" class="form-control custom-input-prop @error('owner_name') is-invalid @enderror" placeholder="Mülk Sahibi İsim / Unvan" value="{{ old('owner_name') }}">
                        @error('owner_name') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Yasal Durum <span class="text-danger">*</span></label>
                        <select name="legal_status" class="form-select custom-input-prop @error('legal_status') is-invalid @enderror" required>
                            <option value="clear" {{ old('legal_status', 'clear') == 'clear' ? 'selected' : '' }}>🟢 Temiz (Sorunsuz)</option>
                            <option value="disputed" {{ old('legal_status') == 'disputed' ? 'selected' : '' }}>🔴 Anlaşmazlık Var (Davalı)</option>
                            <option value="under_process" {{ old('legal_status') == 'under_process' ? 'selected' : '' }}>🟡 İşlem Altında (İntikal/İfraz)</option>
                            <option value="restricted" {{ old('legal_status') == 'restricted' ? 'selected' : '' }}>⚪ Kısıtlı (İpotek/İpoteğe Tabi)</option>
                        </select>
                        @error('legal_status') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Tapu Şerh / İpotek Açıklaması</label>
                        <textarea name="deed_description" class="form-control custom-input-prop @error('deed_description') is-invalid @enderror" rows="3" placeholder="Tapu kütüğündeki şerh, ipotek veya hak sahibi açıklamaları...">{{ old('deed_description') }}</textarea>
                        @error('deed_description') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Hukuki Yasal Notlar</label>
                        <textarea name="legal_notes" class="form-control custom-input-prop @error('legal_notes') is-invalid @enderror" rows="3" placeholder="Avukat ve mahkeme süreç notları...">{{ old('legal_notes') }}</textarea>
                        @error('legal_notes') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 5: DEĞERLENDİRME & RİSK -->
        <div class="card custom-prop-card">
            <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-money-dollar-circle-fill text-success fs-5"></i>5. Ekspertiz Değeri & Yatırım Risk Analizi
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label-custom">Güncel Arsa Değeri (₺)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" name="land_value" step="0.01" class="form-control custom-input-prop @error('land_value') is-invalid @enderror" placeholder="0.00" value="{{ old('land_value') }}">
                        </div>
                        @error('land_value') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Geliştirme Potansiyel Değeri (₺)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" name="development_potential_value" step="0.01" class="form-control custom-input-prop @error('development_potential_value') is-invalid @enderror" placeholder="0.00" value="{{ old('development_potential_value') }}">
                        </div>
                        @error('development_potential_value') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-custom">Yatırım Risk Seviyesi <span class="text-danger">*</span></label>
                        <select name="investment_risk_level" class="form-select custom-input-prop @error('investment_risk_level') is-invalid @enderror" required>
                            <option value="low" {{ old('investment_risk_level', 'low') == 'low' ? 'selected' : '' }}>🟢 Düşük Risk (Güvenli)</option>
                            <option value="medium" {{ old('investment_risk_level') == 'medium' ? 'selected' : '' }}>🟡 Orta Risk</option>
                            <option value="high" {{ old('investment_risk_level') == 'high' ? 'selected' : '' }}>🔴 Yüksek Risk</option>
                        </select>
                        @error('investment_risk_level') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Portföy Operasyonel Durumu <span class="text-danger">*</span></label>
                        <select name="status" class="form-select custom-input-prop @error('status') is-invalid @enderror" required>
                            <option value="vacant" {{ old('status', 'vacant') == 'vacant' ? 'selected' : '' }}>🟡 Boş (İnşaata Hazır)</option>
                            <option value="acquired" {{ old('status') == 'acquired' ? 'selected' : '' }}>🔵 Satın Alındı</option>
                            <option value="in_development" {{ old('status') == 'in_development' ? 'selected' : '' }}>🟠 Geliştirme Aşamasında</option>
                            <option value="developed" {{ old('status') == 'developed' ? 'selected' : '' }}>🟢 Geliştirildi (Tamamlandı)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>⚪ İnaktif</option>
                        </select>
                        @error('status') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Geliştirme Potansiyeli Notları</label>
                        <textarea name="potential_notes" class="form-control custom-input-prop @error('potential_notes') is-invalid @enderror" rows="3" placeholder="Geliştirme vizyonu, proje türü ve fizibilite notları...">{{ old('potential_notes') }}</textarea>
                        @error('potential_notes') <div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- SUBMIT BUTTONS -->
        <div class="d-flex justify-content-between align-items-center mb-5 pt-3 border-top">
            <a href="{{ route('operations.properties.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                İptal
            </a>
            <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                <i class="ri-save-fill me-1"></i>Arsa Kaydını Oluştur & Yayınla
            </button>
        </div>
    </form>

</div>
@endsection
