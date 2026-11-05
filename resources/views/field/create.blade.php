@extends('layouts.master')

@section('title', 'Yeni Günlük Saha Raporu')

@section('topbar-title', 'Saha Operasyonları - Yeni Rapor')

@push('styles')
<style>
    .field-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #047857 50%, #059669 100%);
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
                <a href="{{ route('field.operations') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Saha Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Günlük Saha Raporu Ekle</h1>
            <p class="text-white-50 mb-0 fs-6">Saha puantajını, iş makinesi kullanım durumunu ve notları kaydedin.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="field-form-card">
            <form action="{{ route('api.field.reports.store') }}" method="POST" id="fieldReportForm">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="project_id" class="form-label fw-bold small text-dark">Proje Seçin <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-building-line text-emerald" style="color:#059669;"></i></span>
                            <select name="project_id" id="project_id" class="form-select" required>
                                <option value="">Proje seçin...</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="report_date" class="form-label fw-bold small text-dark">Rapor Tarihi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-calendar-line text-primary"></i></span>
                            <input type="date" name="report_date" id="report_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label fw-bold small text-dark">Günün Notları & Şantiye İlerleme Özeti</label>
                    <textarea name="notes" id="notes" class="form-control" rows="5" placeholder="Bugün sahada gerçekleştirilen imalatlar, yaşanan teknik aksaklıklar veya İSG notları..."></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('field.operations') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#059669;">
                        <i class="ri-save-line me-1"></i>Raporu Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
