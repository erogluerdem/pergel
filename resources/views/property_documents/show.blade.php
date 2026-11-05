@extends('layouts.master')

@section('title', 'Resmi Belge Detayı — ' . $document->title)

@section('topbar-title', 'Gayrimenkul - Belge Detayı')

@push('styles')
<style>
    .pd-show-hero {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .pd-show-hero,
    :root:not([data-bs-theme="dark"]) .pd-show-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .pd-show-hero {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    .pd-card-modern {
        border-radius: 18px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .pd-card-modern,
    :root:not([data-bs-theme="dark"]) .pd-card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .pd-card-modern {
        background: rgba(30, 41, 59, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="pd-show-hero">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                @if($document->status == 'processed')
                    <span class="badge bg-success rounded-pill px-3 py-1 fw-bold">✓ İŞLENDİ & GEÇERLİ</span>
                @elseif($document->status == 'received')
                    <span class="badge bg-info rounded-pill px-3 py-1 fw-bold">📥 ALINDI</span>
                @elseif($document->status == 'expired')
                    <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold">❌ SÜRESİ DOLDU</span>
                @else
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold">⏳ BEKLEMEDE</span>
                @endif

                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-article-line me-1"></i>{{ $document->getTypeLabel() }}
                </span>
            </div>
            <h2 class="fw-bold text-white mb-1">
                {{ $document->title }}
            </h2>
            <p class="text-white-50 mb-0">
                <i class="ri-hashtag me-1 text-warning"></i>
                Belge No: <strong class="text-white">{{ $document->document_number ?? 'Belirtilmedi' }}</strong> |
                <i class="ri-building-line me-1 text-warning"></i>
                Veren Kurum: <strong class="text-white">{{ $document->issued_by ?? 'Kurum Girilmedi' }}</strong>
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('property_documents.edit', $document->id) }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                <i class="ri-edit-line me-1"></i>Düzenle
            </a>
            <a href="{{ route('property_documents.index') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="ri-arrow-left-line me-1"></i>Belgeler Listesi
            </a>
        </div>
    </div>
</div>

<!-- 3 Spec Metric Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="pd-card-modern text-center">
            <small class="text-muted fw-bold text-uppercase d-block mb-1">Harç / Belge Bedeli</small>
            <div class="fs-4 fw-extrabold text-success">
                {{ $document->document_cost > 0 ? ('₺' . number_format($document->document_cost, 0, ',', '.')) : 'Harç Yok / Belirtilmedi' }}
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="pd-card-modern text-center">
            <small class="text-muted fw-bold text-uppercase d-block mb-1">Düzenleme Tarihi</small>
            <div class="fs-4 fw-extrabold text-primary">
                {{ $document->issue_date ? $document->issue_date->format('d.m.Y') : '-' }}
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="pd-card-modern text-center">
            <small class="text-muted fw-bold text-uppercase d-block mb-1">Geçerlilik Bitiş Tarihi</small>
            <div class="fs-4 fw-extrabold {{ $document->is_expired ? 'text-danger' : ($document->is_expiring_soon ? 'text-warning' : 'text-info') }}">
                {{ $document->expiry_date ? $document->expiry_date->format('d.m.Y') : 'Süresiz Belge' }}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Specs & Notes -->
    <div class="col-lg-8">
        <div class="pd-card-modern mb-4">
            <h5 class="fw-bold mb-4">
                <i class="ri-shield-check-line text-primary me-2"></i>Resmi Evrak Detay Künyesi
            </h5>
            
            <div class="row g-3">
                <div class="col-md-6 border-bottom pb-3">
                    <small class="text-muted d-block">Belge Başlığı</small>
                    <strong class="fs-6 text-dark">{{ $document->title }}</strong>
                </div>
                <div class="col-md-6 border-bottom pb-3">
                    <small class="text-muted d-block">Resmi Evrak Türü</small>
                    <span class="fw-bold text-primary">{{ $document->getTypeLabel() }}</span>
                </div>

                <div class="col-md-6 border-bottom pb-3">
                    <small class="text-muted d-block">Belge Numarası / Ruhsat No</small>
                    <span class="font-monospace fw-bold text-dark">{{ $document->document_number ?? 'Kayıtlı Değil' }}</span>
                </div>
                <div class="col-md-6 border-bottom pb-3">
                    <small class="text-muted d-block">Düzenleyen Kurum / Kuruluş</small>
                    <span class="fw-semibold text-dark">{{ $document->issued_by ?? 'Kayıtlı Değil' }}</span>
                </div>

                <div class="col-md-6 pb-2">
                    <small class="text-muted d-block">Kritik Belge Statüsü</small>
                    @if($document->is_critical)
                        <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold">⚠️ Kritik Resmi Belge</span>
                    @else
                        <span class="badge bg-secondary bg-opacity-20 text-muted rounded-pill px-3 py-1">Standart Belge</span>
                    @endif
                </div>
                <div class="col-md-6 pb-2">
                    <small class="text-muted d-block">Yenileme Gereksinimi</small>
                    @if($document->requires_renewal)
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold">🔄 Süreli / Yenilenecek</span>
                    @else
                        <span class="badge bg-success bg-opacity-20 text-success rounded-pill px-3 py-1 fw-bold">✓ Süresiz Kalıcı</span>
                    @endif
                </div>
            </div>
        </div>

        @if($document->description)
            <div class="pd-card-modern">
                <h5 class="fw-bold mb-3">
                    <i class="ri-file-text-line text-warning me-2"></i>Açıklama & Detay Notları
                </h5>
                <div class="p-3 bg-light bg-opacity-50 border rounded-4 text-dark fs-6" style="line-height: 1.7;">
                    {{ $document->description }}
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Related Models, Downloads, Actions -->
    <div class="col-lg-4">
        <!-- Related Models Card -->
        <div class="pd-card-modern mb-4">
            <h5 class="fw-bold mb-3">
                <i class="ri-links-line text-info me-2"></i>Bağlı Kayıtlar
            </h5>

            @if($document->project)
                <div class="mb-3">
                    <small class="text-muted d-block">Bağlı Proje</small>
                    <strong class="fs-6 text-primary"><i class="ri-building-line me-1"></i>{{ $document->project->name }}</strong>
                </div>
            @endif

            @if($document->asset)
                <div class="mb-3">
                    <small class="text-muted d-block">Bağlı Gayrimenkul / Varlık</small>
                    <strong class="fs-6 text-dark"><i class="ri-community-line me-1"></i>{{ $document->asset->name }}</strong>
                </div>
            @endif

            @if(!$document->project && !$document->asset)
                <span class="text-muted">Proje veya gayrimenkul eşleşmesi yapılmamış.</span>
            @endif
        </div>

        <!-- Digital File Download Card -->
        @if($document->file_path)
            <div class="pd-card-modern mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="ri-download-cloud-2-line text-success me-2"></i>Dijital Belge Dosyası
                </h5>
                <p class="small text-muted mb-3">
                    Dosya Adı: <strong class="text-dark">{{ $document->file_name ?? 'Doküman PDF' }}</strong>
                </p>
                <a href="{{ asset('storage/' . $document->file_path) }}" class="btn btn-success w-100 rounded-pill py-2 fw-bold shadow-sm" target="_blank">
                    <i class="ri-download-line me-1"></i>Dosyayı İndir / Görüntüle
                </a>
            </div>
        @endif

        <!-- Delete Action Card -->
        <div class="pd-card-modern border-danger border-opacity-30">
            <h6 class="fw-bold text-danger mb-3">
                <i class="ri-delete-bin-line me-1"></i>Tehlikeli İşlemler
            </h6>
            <form action="{{ route('property_documents.destroy', $document->id) }}" method="POST" class="d-grid">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger rounded-pill py-2" onclick="return confirm('Bu resmi evrak kaydını silmek istediğinizden emin misiniz?')">
                    <i class="ri-delete-bin-line me-1"></i>Belgeyi Kalıcı Olarak Sil
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

