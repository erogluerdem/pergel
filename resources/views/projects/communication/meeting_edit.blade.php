@extends('layouts.master')

@section('title', 'Toplantıyı Düzenle — ' . $meeting->title)

@section('topbar-title', 'Projeler - Toplantı Düzenle')

@push('styles')
<style>
    .comm-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #047857 50%, #10b981 100%);
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
                <a href="{{ route('projects.communication.meetings', ['project' => $project->id]) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Toplantılar Listesi
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Toplantıyı Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">{{ $meeting->title }}</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="comm-form-card">
            <form method="POST" action="{{ route('projects.communication.meetings.update', ['project' => $project->id, 'meeting' => $meeting->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-7">
                        <label class="form-label fw-bold small text-dark">Toplantı Başlığı <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-team-line text-success"></i></span>
                            <input name="title" class="form-control" value="{{ old('title', $meeting->title) }}" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold small text-dark">Toplantı Tarihi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-calendar-line text-primary"></i></span>
                            <input type="date" name="meeting_date" class="form-control" value="{{ old('meeting_date', $meeting->meeting_date ? $meeting->meeting_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Katılımcı E-Postaları (Virgülle Ayrılmış)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-user-follow-line text-warning"></i></span>
                        <input name="participants" class="form-control" value="{{ old('participants', implode(', ', $meeting->participants ?: [])) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Toplantı Kararları & Notlar</label>
                    <textarea name="notes" class="form-control" rows="6">{{ old('notes', $meeting->notes) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-dark">Yeni Ekler Yükle</label>
                    <input type="file" name="attachments[]" class="form-control" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('projects.communication.meetings', ['project' => $project->id]) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#10b981;">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
