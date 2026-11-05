@extends('layouts.master')

@section('title', 'Satın Alma Onay Süreci Detayı')

@section('topbar-title', 'Satın Alma Onayları - Detay')

@push('styles')
<style>
    .approval-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #b45309 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .approval-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .approval-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $reqNo = 'REQ-2026-' . sprintf('%04d', $approval->requisition_id);
    $reqNotes = $approval->requisition->notes ?? 'Satın Alma Talebi Açıklaması';
@endphp

<!-- Hero Banner -->
<div class="approval-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('purchasing.approvals.pending') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Onaylar Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $reqNo }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Satın Alma Onay Süreci Detayı</h1>
            <p class="text-white-50 mb-0 fs-6">Proje: {{ $approval->requisition->project->name ?? 'Şantiye Projesi' }} — Yetkili: {{ $approval->approver->name ?? 'Onay Yetkilisi' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('purchasing.approvals.edit', $id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>Süreci Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="approval-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $reqNotes }}</h3>
                    <small class="text-muted">Onay Adımı Oluşturma: {{ $approval->created_at ? $approval->created_at->format('d.m.Y H:i') : date('d.m.Y H:i') }}</small>
                </div>
                <div>
                    @if($approval->status === 'approved')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Onaylandı</span>
                    @elseif($approval->status === 'rejected')
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Reddedildi</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Onay Bekliyor</span>
                    @endif
                </div>
            </div>

            <!-- Summary Specs -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Onay Seviyesi / Kademe</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ ucfirst($approval->approval_level) }} Kademe</h4>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Onay Yetkilisi</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $approval->approver->name ?? 'Onay Yetkilisi' }}</h4>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Son İşlem Tarihi</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $approval->approved_at ? $approval->approved_at->format('d.m.Y H:i') : ($approval->rejected_at ? $approval->rejected_at->format('d.m.Y H:i') : 'Beklemede') }}</h4>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Onay Yetkilisi Notları & Değerlendirme</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark mb-4" style="white-space: pre-line;">
                {{ $approval->comments ?: ($approval->rejection_reason ?: 'Yetkili değerlendirme notu henüz girilmemiş.') }}
            </div>
        </div>
    </div>
</div>

@endsection
