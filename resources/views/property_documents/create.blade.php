@extends('layouts.master')

@section('title', 'Yeni Tapu & Gayrimenkul Belgesi Ekle')

@push('styles')
<style>
    .pd-create-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .pd-create-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: pdCreateGlow 4s linear infinite;
    }

    @keyframes pdCreateGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .pd-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-pd-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    .custom-input-pd {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-pd:focus {
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
    <div class="pd-create-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill pd-badge-pill">
                        <i class="ri-shield-keyhole-fill text-warning fs-5"></i> GAYRİMENKUL & TAPU YÖNETİMİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        Yeni Resmi Belge Kaydı
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Tapu & Gayrimenkul Belgesi Kaydet</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Gayrimenkul veya projeye ait tapu, iskan, ruhsat veya abonelik evraklarını sisteme kaydedin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('property_documents.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Resmi Evrak Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card custom-pd-card mb-5">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-file-add-fill text-warning fs-5"></i>Evrak Kayıt Formu
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('property_documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <!-- Project -->
                    <div class="col-md-6">
                        <label for="project_id" class="form-label-custom">İlişkili Proje</label>
                        <select class="form-select custom-input-pd @error('project_id') is-invalid @enderror" name="project_id" id="project_id">
                            <option value="">-- Proje Seçiniz (Opsiyonel) --</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }}>
                                    🏗️ {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Asset -->
                    <div class="col-md-6">
                        <label for="asset_id" class="form-label-custom">İlişkili Gayrimenkul / Varlık</label>
                        <select class="form-select custom-input-pd @error('asset_id') is-invalid @enderror" name="asset_id" id="asset_id">
                            <option value="">-- Gayrimenkul Seçiniz (Opsiyonel) --</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                    🏡 {{ $asset->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('asset_id')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Document Type -->
                    <div class="col-md-6">
                        <label for="document_type" class="form-label-custom">Resmi Evrak Türü <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-pd @error('document_type') is-invalid @enderror" name="document_type" id="document_type" required>
                            <option value="">-- Belge Türü Seçiniz --</option>
                            <option value="tapu" {{ old('document_type') == 'tapu' ? 'selected' : '' }}>📜 Tapu Belgesi</option>
                            <option value="mesken" {{ old('document_type') == 'mesken' ? 'selected' : '' }}>🏠 Mesken / İskan Belgesi</option>
                            <option value="imar" {{ old('document_type') == 'imar' ? 'selected' : '' }}>📐 İmar Durum Belgesi & Çap</option>
                            <option value="yapı_ruhsat" {{ old('document_type') == 'yapı_ruhsat' ? 'selected' : '' }}>🏗️ Yapı İnşaat Ruhsatı</option>
                            <option value="yapı_kullanma" {{ old('document_type') == 'yapı_kullanma' ? 'selected' : '' }}>✓ Yapı Kullanma İzin Belgesi</option>
                            <option value="elektrik" {{ old('document_type') == 'elektrik' ? 'selected' : '' }}>⚡ Elektrik Tesisat Uygunluk Raporu</option>
                            <option value="su" {{ old('document_type') == 'su' ? 'selected' : '' }}>💧 Su / İSKİ Bağlantı Belgesi</option>
                            <option value="doğalgaz" {{ old('document_type') == 'doğalgaz' ? 'selected' : '' }}>🔥 Doğalgaz / İGDAŞ Onay Projesi</option>
                        </select>
                        @error('document_type')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Title -->
                    <div class="col-md-6">
                        <label for="title" class="form-label-custom">Belge Başlığı <span class="text-danger">*</span></label>
                        <input type="text" class="form-control custom-input-pd @error('title') is-invalid @enderror" name="title" id="title" 
                               placeholder="Örn: Kat Mülkiyeti Tapu Senedi A Blok D.14" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback d-block fw-bold mt-1">{{ $message }}</div>@enderror
                    </div>

                    <!-- Document Number -->
                    <div class="col-md-6">
                        <label for="document_number" class="form-label-custom">Belge / Ruhsat Numarası</label>
                        <input type="text" class="form-control custom-input-pd" name="document_number" id="document_number" 
                               placeholder="Örn: TP-2025/89412" value="{{ old('document_number') }}">
                    </div>

                    <!-- Issued By -->
                    <div class="col-md-6">
                        <label for="issued_by" class="form-label-custom">Düzenleyen Kurum / Kuruluş</label>
                        <input type="text" class="form-control custom-input-pd" name="issued_by" id="issued_by" 
                               placeholder="Örn: Kadıköy Tapu Müdürlüğü" value="{{ old('issued_by') }}">
                    </div>

                    <!-- Issue Date -->
                    <div class="col-md-4">
                        <label for="issue_date" class="form-label-custom">Düzenleme Tarihi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control custom-input-pd @error('issue_date') is-invalid @enderror" 
                               name="issue_date" id="issue_date" value="{{ old('issue_date', now()->format('Y-m-d')) }}" required>
                    </div>

                    <!-- Expiry Date -->
                    <div class="col-md-4">
                        <label for="expiry_date" class="form-label-custom">Geçerlilik Bitiş Tarihi</label>
                        <input type="date" class="form-control custom-input-pd" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}">
                    </div>

                    <!-- Document Cost -->
                    <div class="col-md-4">
                        <label for="document_cost" class="form-label-custom">Belge Harç / Maliyet (₺)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-warning border-0 fw-bold">₺</span>
                            <input type="number" class="form-control custom-input-pd" name="document_cost" id="document_cost" 
                                   min="0" step="0.01" placeholder="Örn: 18500" value="{{ old('document_cost') }}">
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label-custom">Belge Durumu <span class="text-danger">*</span></label>
                        <select class="form-select custom-input-pd @error('status') is-invalid @enderror" name="status" id="status" required>
                            <option value="processed" {{ old('status', 'processed') == 'processed' ? 'selected' : '' }}>✓ İşlendi / Geçerli</option>
                            <option value="received" {{ old('status') == 'received' ? 'selected' : '' }}>📥 Alındı</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>⏳ Beklemede</option>
                            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>❌ Süresi Doldu</option>
                        </select>
                    </div>

                    <!-- File Upload -->
                    <div class="col-md-6">
                        <label for="file" class="form-label-custom">Dijital Belge Dosyası (PDF / Görsel)</label>
                        <input type="file" class="form-control custom-input-pd" name="file" id="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="text-muted d-block mt-1">Desteklenen formatlar: PDF, Word, JPG, PNG (Maksimum 10MB)</small>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label-custom">Açıklama & Detay Notları</label>
                        <textarea class="form-control custom-input-pd" name="description" id="description" rows="3" 
                                  placeholder="Evraka ait özel detaylar, onay makamı notları ve parsel açıklamaları...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- SUBMIT BUTTONS -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <a href="{{ route('property_documents.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                        İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-save-fill me-1"></i>Belgeyi Kaydet & Arşive Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
