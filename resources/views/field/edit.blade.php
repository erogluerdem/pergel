@extends('layouts.master')

@section('title', 'Saha Raporu Düzenle — ' . ($report->report_date ? $report->report_date->format('d.m.Y') : 'Rapor'))

@section('topbar-title', 'Saha Operasyonları - Rapor Düzenle')

@push('styles')
<style>
    .field-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .field-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .field-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="field-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('field.operations.show', $report->id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Rapor Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Günlük Saha Raporunu Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">{{ $report->report_date ? $report->report_date->format('d.m.Y') : '' }} Tarihli Rapor</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="field-form-card">
            <form action="{{ route('api.field.reports.store') }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $report->project_id }}">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Rapor Tarihi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-calendar-line text-primary"></i></span>
                            <input type="date" name="report_date" class="form-control" value="{{ old('report_date', $report->report_date ? $report->report_date->format('Y-m-d') : '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Rapor Durumu</label>
                        <select name="status" class="form-select">
                            <option value="draft" {{ old('status', $report->status) == 'draft' ? 'selected' : '' }}>Taslak</option>
                            <option value="submitted" {{ old('status', $report->status) == 'submitted' ? 'selected' : '' }}>Onaya Gönderildi</option>
                            <option value="approved" {{ old('status', $report->status) == 'approved' ? 'selected' : '' }}>Onaylandı</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Günün Notları & Şantiye İlerleme Özeti</label>
                    <textarea name="notes" class="form-control" rows="6">{{ old('notes', $report->notes) }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('field.operations.show', $report->id) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
