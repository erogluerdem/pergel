@extends('layouts.master')

@section('title', 'Yeni Garanti Talebi — ' . ($closeout->project->name ?? 'Proje'))

@section('topbar-title', 'Proje Yönetimi - Garanti Talebi')

@push('styles')
<style>
    .closeout-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #047857 50%, #10b981 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .closeout-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .closeout-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="closeout-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('closeout.show', $closeout) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Kapanış Detayları
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Garanti Talebi Oluştur</h1>
            <p class="text-white-50 mb-0 fs-6">{{ $closeout->project->name ?? 'Proje' }} — Garanti Süreci Arıza veya Bakım Bildirimi</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="closeout-form-card">
            <form action="{{ route('closeout.store-warranty-claim', $closeout) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="subject" class="form-label fw-bold small text-dark">Talep Konusu / Başlık <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-shield-flash-line text-warning"></i></span>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror"
                               value="{{ old('subject') }}" placeholder="Örn: A-Blok 3. Kat VRF İç Ünite Drenaj Tavası Sızıntısı" required>
                    </div>
                    @error('subject')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="notification_date" class="form-label fw-bold small text-dark">Bildirim Tarihi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-calendar-line text-primary"></i></span>
                            <input type="date" name="notification_date" id="notification_date" class="form-control @error('notification_date') is-invalid @enderror"
                                   value="{{ old('notification_date', date('Y-m-d')) }}" required>
                        </div>
                        @error('notification_date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="equipment_id" class="form-label fw-bold small text-dark">İlgili Ekipman / Cihaz</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-tools-line text-info"></i></span>
                            <select name="equipment_id" id="equipment_id" class="form-select @error('equipment_id') is-invalid @enderror">
                                <option value="">Ekipman seçin...</option>
                                @foreach($equipments as $equipment)
                                    <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-muted small mt-1 d-block">Ekipman seçildiğinde sorumlu taşeron otomatik olarak atanır.</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="evidence_photo" class="form-label fw-bold small text-dark">Arıza & Kanıt Fotoğrafı</label>
                    <input type="file" name="evidence_photo" id="evidence_photo" class="form-control @error('evidence_photo') is-invalid @enderror" accept="image/*">
                    @error('evidence_photo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold small text-dark">Açıklama & Arıza Detayları</label>
                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Arızanın oluşum şekli, etkilenen mahaller ve teknik ayrıntılar...">{{ old('description') }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('closeout.show', $closeout) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#10b981;">
                        <i class="ri-save-line me-1"></i>Garanti Talebini Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
