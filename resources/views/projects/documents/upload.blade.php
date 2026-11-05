@extends('layouts.master')

@section('title', 'Doküman Yükle — ' . $project->name)

@section('topbar-title', 'Projeler - Doküman Yükleme')

@push('styles')
<style>
    .doc-upload-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .doc-upload-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: docUploadGlow 4s linear infinite;
    }

    @keyframes docUploadGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .doc-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-doc-upload-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .custom-input-doc {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-doc:focus {
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

    .mode-radio-box {
        background: #f8fafc !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 14px !important;
        padding: 16px 20px !important;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .mode-radio-box:hover {
        border-color: #d97706 !important;
        background: #fffbeb !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="doc-upload-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill doc-badge-pill">
                        <i class="ri-upload-cloud-2-fill text-warning fs-5"></i> DİJİTAL DOKÜMAN VE PROJE ARŞİVİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        PROJE: {{ $project->name }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Yeni Doküman Yükleme Formu</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Projeye ait mimari, statik, tesisat, CAD veya resmi imar evraklarını sürümleriyle dijital arşive aktarın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('projects.documents.index', $project->id) }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Doküman Listesine Dön
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN UPLOAD FORM CARD -->
    <div class="row">
        <div class="col-lg-10 col-xl-9 mx-auto">
            <div class="card custom-doc-upload-card mb-5">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-file-upload-fill text-warning fs-5"></i>Doküman Yükleme & Revizyon Tanımları
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('projects.documents.store', $project->id) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- SECTION 1: MODE SELECTION -->
                        <div class="border-bottom pb-4 mb-4">
                            <label class="form-label-custom">Yükleme Tipi <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mode-radio-box d-flex align-items-center gap-3">
                                        <input class="form-check-input" type="radio" name="mode" id="mode_new" value="new" checked>
                                        <label class="form-check-label fw-extrabold text-dark cursor-pointer mb-0" for="mode_new">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="ri-file-add-fill text-primary fs-5"></i>
                                                <span style="font-size: 1rem;">Yeni Sıfır Doküman Kaydı</span>
                                            </div>
                                            <small class="text-muted fw-semibold d-block">Projeye tamamen yeni bir evrak ekleyin.</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mode-radio-box d-flex align-items-center gap-3">
                                        <input class="form-check-input" type="radio" name="mode" id="mode_version" value="version">
                                        <label class="form-check-label fw-extrabold text-dark cursor-pointer mb-0" for="mode_version">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <i class="ri-history-fill text-warning fs-5"></i>
                                                <span style="font-size: 1rem;">Mevcut Dokümana Sürüm / Revizyon Ekle</span>
                                            </div>
                                            <small class="text-muted fw-semibold d-block">Kayıtlı bir evrakın üst sürümünü (V2, V3) yükleyin.</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: NEW DOCUMENT FIELDS -->
                        <div id="new-fields" class="border-bottom pb-4 mb-4">
                            <h6 class="fw-extrabold text-primary mb-3 d-flex align-items-center gap-2">
                                <i class="ri-information-fill text-warning"></i> Doküman Kimlik Bilgileri
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label for="name" class="form-label-custom">Doküman Adı / Tanımı <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control custom-input-doc" placeholder="Örn: Zemin Kat Kalıp ve Donatı Planı">
                                </div>
                                <div class="col-md-5">
                                    <label for="code" class="form-label-custom">Dosya Kod Numarası</label>
                                    <input type="text" name="code" id="code" class="form-control custom-input-doc font-monospace" placeholder="Örn: STR-2026-001">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: VERSION FIELDS (HIDDEN BY DEFAULT) -->
                        <div id="version-fields" class="border-bottom pb-4 mb-4" style="display:none;">
                            <h6 class="fw-extrabold text-primary mb-3 d-flex align-items-center gap-2">
                                <i class="ri-history-fill text-warning"></i> Revize Edilecek Mevcut Doküman
                            </h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="document_id" class="form-label-custom">Hedef Dokümanı Seçiniz <span class="text-danger">*</span></label>
                                    <select name="document_id" id="document_id" class="form-select custom-input-doc">
                                        <option value="">-- Doküman Seçiniz --</option>
                                        @foreach($documents as $doc)
                                            <option value="{{ $doc->id }}">📄 {{ $doc->name }} (Mevcut Sürüm: {{ $doc->version ?? 'V1.0' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: FILE UPLOAD DROPZONE & SETTINGS -->
                        <div class="mb-4">
                            <h6 class="fw-extrabold text-primary mb-3 d-flex align-items-center gap-2">
                                <i class="ri-upload-2-fill text-warning"></i> Dosya Yükleme & Dizin Ayarları
                            </h6>
                            <div class="mb-4">
                                <label for="file" class="form-label-custom">Yüklenecek Dosya (PDF, DWG, CAD, ZIP, PNG, JPG) <span class="text-danger">*</span></label>
                                <input type="file" name="file" id="file" class="form-control custom-input-doc" required>
                                <small class="text-muted fw-bold mt-1 d-block"><i class="ri-information-line me-1"></i>Maksimum dosya boyutu: 50MB.</small>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="folder_id" class="form-label-custom">Hedef Klasör</label>
                                    <select name="folder_id" id="folder_id" class="form-select custom-input-doc">
                                        <option value="">-- Ana Dizin (Klasör Yok) --</option>
                                        @foreach($folders as $folder)
                                            <option value="{{ $folder->id }}">📁 {{ $folder->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="version" class="form-label-custom">Versiyon Etiketi</label>
                                    <input type="text" name="version" id="version" class="form-control custom-input-doc font-monospace" value="V1.0">
                                </div>

                                <div class="col-md-4">
                                    <label for="discipline" class="form-label-custom">Disiplin / Kategori</label>
                                    <select name="discipline" id="discipline" class="form-select custom-input-doc">
                                        <option value="Mimari">📐 Mimari Proje</option>
                                        <option value="Statik">🧱 Statik & Betonarme</option>
                                        <option value="Mekanik">🔧 Mekanik & Tesisat</option>
                                        <option value="Elektrik">⚡ Elektrik Projesi</option>
                                        <option value="Ruhsat & İdari">🏛️ Ruhsat & İdari</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="status" class="form-label-custom">Onay / Evrak Durumu</label>
                                    <select name="status" id="status" class="form-select custom-input-doc">
                                        <option value="approved">✅ Onaylı (Approved)</option>
                                        <option value="review">⏳ İnceleniyor (Review)</option>
                                        <option value="draft">📝 Taslak (Draft)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SUBMIT BUTTONS -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('projects.documents.index', $project->id) }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                                İptal
                            </a>
                            <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                                <i class="ri-upload-cloud-fill me-1"></i>Dokümanı Yükle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="mode"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'version') {
                document.getElementById('version-fields').style.display = 'block';
                document.getElementById('new-fields').style.display = 'none';
            } else {
                document.getElementById('version-fields').style.display = 'none';
                document.getElementById('new-fields').style.display = 'block';
            }
        });
    });
});
</script>
@endpush
