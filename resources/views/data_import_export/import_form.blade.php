@extends('layouts.master')

@section('title', 'Veri İçe Aktar (Import)')

@section('topbar-title', 'Veri Transferi - İçe Aktar')

@push('styles')
<style>
    .die-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .die-glass-card {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .die-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .dropzone-area {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 3rem;
        text-align: center;
        background: rgba(248, 250, 252, 0.6);
        transition: border-color 0.3s ease;
    }

    .dropzone-area:hover {
        border-color: #2563eb;
    }
</style>
@endpush

@section('content')

<div class="die-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('data-import-export.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Veri Transfer Merkezi
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Toplu Veri İçe Aktar (Import)</h1>
            <p class="text-white-50 mb-0 fs-6">Excel (.xlsx), CSV veya JSON dosyalarınızı seçtiğiniz modüle yükleyin.</p>
        </div>
    </div>
</div>

<div class="die-glass-card">
    <form method="POST" action="{{ route('data-import-export.import') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label fw-bold text-dark">Aktarılacak Hedef Modül <span class="text-danger">*</span></label>
                <select name="module" class="form-select form-select-lg @error('module') is-invalid @enderror" required>
                    <option value="">-- Modül Seçiniz --</option>
                    @foreach($modules as $key => $label)
                        <option value="{{ $key }}" {{ old('module') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('module') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-bold text-dark">Dosya Yükle (Excel / CSV / JSON) <span class="text-danger">*</span></label>
                <div class="dropzone-area mb-3">
                    <i class="ri-upload-cloud-2-line text-primary" style="font-size: 3.5rem;"></i>
                    <h5 class="fw-bold text-dark mt-2 mb-1">Dosyanızı buraya sürükleyin veya seçin</h5>
                    <p class="text-muted small mb-3">Desteklenen formatlar: .xlsx, .csv, .json (Maks: 10 MB)</p>
                    <input type="file" name="file" class="form-control w-50 mx-auto @error('file') is-invalid @enderror" required>
                    @error('file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('data-import-export.index') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
            <button type="submit" class="btn btn-primary text-white rounded-pill px-5 fw-bold">
                <i class="ri-upload-2-line me-1"></i>Yüklemeyi Başlat
            </button>
        </div>
    </form>
</div>

@endsection
