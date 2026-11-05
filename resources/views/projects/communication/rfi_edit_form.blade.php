@extends('layouts.master')

@section('title', 'RFI Düzenle — ' . ($rfi ? $rfi->rfi_no : ''))

@section('topbar-title', 'Projeler - RFI Düzenle')

@push('styles')
<style>
    .comm-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0284c7 50%, #06b6d4 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .comm-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .comm-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="comm-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('projects.communication.rfis', ['project' => $project->id]) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>RFI Listesi
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">RFI Talebini Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">{{ $rfi ? $rfi->rfi_no : '' }} — {{ $rfi ? $rfi->subject : '' }}</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="comm-form-card">
            @if($rfi)
                <form action="{{ route('projects.communication.rfis.update', ['project' => $project->id, 'rfi' => $rfi->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold small text-dark">Konu / Teknik Başlık <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-question-line text-info"></i></span>
                                <input type="text" name="subject" class="form-control" value="{{ old('subject', $rfi->subject) }}" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-dark">Atanacak Kişi / Mühendis</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-user-follow-line text-warning"></i></span>
                                <input type="email" name="assigned_to" class="form-control" value="{{ old('assigned_to', $rfi->assigned_to) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Son Yanıt Tarihi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-calendar-line text-primary"></i></span>
                                <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $rfi->due_date ? $rfi->due_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">RFI Durumu</label>
                            <select name="status" class="form-select">
                                <option value="open" {{ old('status', $rfi->status) == 'open' ? 'selected' : '' }}>Açık</option>
                                <option value="pending" {{ old('status', $rfi->status) == 'pending' ? 'selected' : '' }}>İncelemede / Bekliyor</option>
                                <option value="closed" {{ old('status', $rfi->status) == 'closed' ? 'selected' : '' }}>Kapalı / Yanıtlandı</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Açıklama & Soru Detayları</label>
                        <textarea name="description" class="form-control" rows="5">{{ old('description', $rfi->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">Yeni Ekler Yükle</label>
                        <input type="file" name="attachments[]" class="form-control" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                        <a href="{{ route('projects.communication.rfis', ['project' => $project->id]) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                        <button type="submit" class="btn btn-info text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#0284c7;">
                            <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-5 text-muted">RFI Bulunamadı.</div>
            @endif
        </div>
    </div>
</div>

@endsection
