@extends('layouts.master')

@section('title', 'Onay Adımını Düzenle')

@section('topbar-title', 'Satın Alma Onayları - Düzenle')

@push('styles')
<style>
    .approval-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #b45309 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .approval-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .approval-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="approval-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('purchasing.approvals.show', $id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Süreç Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Satın Alma Onay Adımını Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">Onay durumu, yetkili kullanıcı ataması ve onay yorumlarını güncelleyin.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="approval-form-card">
            <form action="{{ route('purchasing.approvals.pending') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="approval_level" class="form-label fw-bold small text-dark">Onay Kademesi <span class="text-danger">*</span></label>
                        <select name="approval_level" id="approval_level" class="form-select" required>
                            <option value="manager" {{ ($approval->approval_level ?? '') === 'manager' ? 'selected' : '' }}>1. Kademe: Proje Müdürü</option>
                            <option value="finance" {{ ($approval->approval_level ?? '') === 'finance' ? 'selected' : '' }}>2. Kademe: Finans Bütçe</option>
                            <option value="executive" {{ ($approval->approval_level ?? '') === 'executive' ? 'selected' : '' }}>3. Kademe: Genel Müdürlük</option>
                            <option value="final" {{ ($approval->approval_level ?? '') === 'final' ? 'selected' : '' }}>Son Seviye: Yönetim Kurulu</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold small text-dark">Onay Durumu</label>
                        <select id="status" class="form-select">
                            <option value="pending" {{ ($approval->status ?? '') === 'pending' ? 'selected' : '' }}>Onay Bekliyor</option>
                            <option value="approved" {{ ($approval->status ?? '') === 'approved' ? 'selected' : '' }}>Onaylandı</option>
                            <option value="rejected" {{ ($approval->status ?? '') === 'rejected' ? 'selected' : '' }}>Reddedildi</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="comments" class="form-label fw-bold small text-dark">Yetkili Yorumu & Gerekçe</label>
                    <textarea id="comments" class="form-control" rows="4">{{ old('comments', $approval->comments ?? ($approval->rejection_reason ?? 'Saha proje müdürü teknik inceleme ve fiyat değerlendirme aşamasında.')) }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('purchasing.approvals.show', $id) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
