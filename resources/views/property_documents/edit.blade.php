@extends('layouts.master')

@section('title', 'Belge Düzenle — ' . $document->title)

@section('topbar-title', 'Gayrimenkul - Belge Düzenle')

@push('styles')
<style>
    .pd-form-card {
        border-radius: 20px;
        padding: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .pd-form-card,
    :root:not([data-bs-theme="dark"]) .pd-form-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .pd-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .pd-form-label {
        font-weight: 700;
        font-size: 0.88rem;
        margin-bottom: 0.4rem;
    }

    html[data-bs-theme="light"] .pd-form-label,
    :root:not([data-bs-theme="dark"]) .pd-form-label { color: #1e293b; }
    html[data-bs-theme="dark"] .pd-form-label { color: #f8fafc; }

    .pd-form-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }

    .pd-icon-ring {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.15) 100%);
        color: #f59e0b;
    }
</style>
@endpush

@section('content')

<div class="row">
    <div class="col-lg-10 col-xl-9 mx-auto">
        <div class="pd-form-card">
            <!-- Header -->
            <div class="pd-form-header">
                <div class="pd-icon-ring">
                    <i class="ri-edit-box-line"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">Resmi Evrak Düzenle</h3>
                    <p class="text-muted mb-0 small">Kayıtlı belgenin durumunu, tarihlerini, harç bilgilerini veya dijital dosyasını güncelleyin.</p>
                </div>
            </div>

            <form action="{{ route('property_documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="project_id" class="pd-form-label">İlişkili Proje</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="ri-building-line"></i></span>
                            <select class="form-select @error('project_id') is-invalid @enderror" name="project_id" id="project_id">
                                <option value="">-- Proje Seçiniz (Opsiyonel) --</option>
                                @foreach($projects as $proj)
                                    <option value="{{ $proj->id }}" {{ old('project_id', $document->project_id) == $proj->id ? 'selected' : '' }}>
                                        {{ $proj->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('project_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="asset_id" class="pd-form-label">İlişkili Gayrimenkul / Varlık</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="ri-community-line"></i></span>
                            <select class="form-select @error('asset_id') is-invalid @enderror" name="asset_id" id="asset_id">
                                <option value="">-- Gayrimenkul Seçiniz (Opsiyonel) --</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}" {{ old('asset_id', $document->asset_id) == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('asset_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="document_type" class="pd-form-label">Resmi Evrak Türü <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="ri-article-line"></i></span>
                            <select class="form-select @error('document_type') is-invalid @enderror" name="document_type" id="document_type" required>
                                <option value="">-- Belge Türü Seçiniz --</option>
                                <option value="tapu" {{ old('document_type', $document->document_type) == 'tapu' ? 'selected' : '' }}>📜 Tapu Belgesi</option>
                                <option value="mesken" {{ old('document_type', $document->document_type) == 'mesken' ? 'selected' : '' }}>🏠 Mesken / İskan Belgesi</option>
                                <option value="imar" {{ old('document_type', $document->document_type) == 'imar' ? 'selected' : '' }}>📐 İmar Durum Belgesi & Çap</option>
                                <option value="yapı_ruhsat" {{ old('document_type', $document->document_type) == 'yapı_ruhsat' ? 'selected' : '' }}>🏗️ Yapı İnşaat Ruhsatı</option>
                                <option value="yapı_kullanma" {{ old('document_type', $document->document_type) == 'yapı_kullanma' ? 'selected' : '' }}>✓ Yapı Kullanma İzin Belgesi</option>
                                <option value="elektrik" {{ old('document_type', $document->document_type) == 'elektrik' ? 'selected' : '' }}>⚡ Elektrik Tesisat Uygunluk Raporu</option>
                                <option value="su" {{ old('document_type', $document->document_type) == 'su' ? 'selected' : '' }}>💧 Su / İSKİ Bağlantı Belgesi</option>
                                <option value="doğalgaz" {{ old('document_type', $document->document_type) == 'doğalgaz' ? 'selected' : '' }}>🔥 Doğalgaz / İGDAŞ Onay Projesi</option>
                            </select>
                        </div>
                        @error('document_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="title" class="pd-form-label">Belge Başlığı <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="ri-t-box-line"></i></span>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" 
                                   placeholder="Örn: Kat Mülkiyeti Tapu Senedi A Blok D.14" value="{{ old('title', $document->title) }}" required>
                        </div>
                        @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="document_number" class="pd-form-label">Belge / Ruhsat Numarası</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="ri-hashtag"></i></span>
                            <input type="text" class="form-control" name="document_number" id="document_number" 
                                   placeholder="Örn: TP-2025/89412" value="{{ old('document_number', $document->document_number) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="issued_by" class="pd-form-label">Düzenleyen Kurum / Kuruluş</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="ri-government-line"></i></span>
                            <input type="text" class="form-control" name="issued_by" id="issued_by" 
                                   placeholder="Örn: Kadıköy Tapu Müdürlüğü" value="{{ old('issued_by', $document->issued_by) }}">
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="issue_date" class="pd-form-label">Düzenleme Tarihi <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                            <input type="date" class="form-control @error('issue_date') is-invalid @enderror" 
                                   name="issue_date" id="issue_date" value="{{ old('issue_date', $document->issue_date ? $document->issue_date->format('Y-m-d') : '') }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="expiry_date" class="pd-form-label">Geçerlilik Bitiş Tarihi</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="ri-calendar-event-line"></i></span>
                            <input type="date" class="form-control" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $document->expiry_date ? $document->expiry_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="document_cost" class="pd-form-label">Belge Harç / Maliyet (₺)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">₺</span>
                            <input type="number" class="form-control" name="document_cost" id="document_cost" 
                                   min="0" step="0.01" placeholder="Örn: 18500" value="{{ old('document_cost', $document->document_cost) }}">
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="status" class="pd-form-label">Belge Durumu <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="ri-flag-line"></i></span>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" id="status" required>
                                <option value="processed" {{ old('status', $document->status) == 'processed' ? 'selected' : '' }}>✓ İşlendi / Geçerli</option>
                                <option value="received" {{ old('status', $document->status) == 'received' ? 'selected' : '' }}>📥 Alındı</option>
                                <option value="pending" {{ old('status', $document->status) == 'pending' ? 'selected' : '' }}>⏳ Beklemede</option>
                                <option value="expired" {{ old('status', $document->status) == 'expired' ? 'selected' : '' }}>❌ Süresi Doldu</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="file" class="pd-form-label">Yeni Dijital Belge Dosyası Yükle</label>
                        @if($document->file_path)
                            <p class="small text-muted mb-2">
                                <i class="ri-file-line me-1"></i>Mevcut Dosya: <strong class="text-dark">{{ $document->file_name ?? 'Doküman PDF' }}</strong>
                            </p>
                        @endif
                        <div class="input-group input-group-lg">
                            <input type="file" class="form-control" name="file" id="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </div>
                        <small class="text-muted">Boş bırakırsanız mevcut dosya değiştirilmez.</small>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="pd-form-label">Açıklama & Detay Notları</label>
                    <textarea class="form-control" name="description" id="description" rows="3" 
                              placeholder="Evraka ait özel detaylar, onay makamı notları...">{{ old('description', $document->description) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top border-opacity-10">
                    <a href="{{ route('property_documents.show', $document->id) }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="ri-close-line me-1"></i>İptal
                    </a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

