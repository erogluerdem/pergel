@extends('layouts.master')

@section('title', 'Yeni Onay Süreci Başlat')

@section('topbar-title', 'Satın Alma Onayları - Yeni Süreç')

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
                <a href="{{ route('purchasing.approvals.pending') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Onaylar Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Satın Alma Onay Adımı Başlat</h1>
            <p class="text-white-50 mb-0 fs-6">Satın alma talebi için onay kademesi ve sorumlu yetkili atama formu.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="approval-form-card">
            <form action="{{ route('purchasing.approvals.pending') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="requisition_id" class="form-label fw-bold small text-dark">Satın Alma Talebi <span class="text-danger">*</span></label>
                        <select name="requisition_id" id="requisition_id" class="form-select" required>
                            <option value="">Talep seçin...</option>
                            @foreach($requisitions as $req)
                                <option value="{{ $req->id }}">REQ-2026-{{ sprintf('%04d', $req->id) }} - {{ Str::limit($req->notes, 35) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="approval_level" class="form-label fw-bold small text-dark">Onay Kademesi <span class="text-danger">*</span></label>
                        <select name="approval_level" id="approval_level" class="form-select" required>
                            <option value="manager">1. Kademe: Proje Müdürü</option>
                            <option value="finance">2. Kademe: Finans Bütçe</option>
                            <option value="executive">3. Kademe: Genel Müdürlük</option>
                            <option value="final">Son Seviye: Yönetim Kurulu</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="approver_id" class="form-label fw-bold small text-dark">Onaycı Yetkili Kullanıcı <span class="text-danger">*</span></label>
                        <select name="approver_id" id="approver_id" class="form-select" required>
                            <option value="">Yetkili seçin...</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold small text-dark">Başlangıç Durumu</label>
                        <select id="status" class="form-select">
                            <option value="pending">Onay Bekliyor</option>
                            <option value="approved">Onaylandı</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="comments" class="form-label fw-bold small text-dark">Onay Süreci Notları & Açıklama</label>
                    <textarea id="comments" class="form-control" rows="4" placeholder="Onay yetkilisine iletilecek özel notlar ve bütçe kısıtları..."></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('purchasing.approvals.pending') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Onay Adımını Başlat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
