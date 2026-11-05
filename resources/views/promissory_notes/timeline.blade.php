@extends('layouts.master')

@section('title', 'Senet & Nakit Akış Vade Takvimi')

@push('styles')
<style>
    .timeline-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .timeline-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-timeline {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .timeline-item-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 14px !important;
        transition: all 0.25s ease !important;
    }
    .timeline-item-card:hover {
        border-color: #d97706 !important;
        transform: translateX(4px) !important;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08) !important;
    }
    .timeline-item-overdue {
        background: #fef2f2 !important;
        border-color: #fca5a5 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 timeline-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 timeline-badge">
                            <i class="ri-calendar-event-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">KRONOLOJİK TAHSİLAT VADE AKIŞI</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Vade & Tahsilat Zaman Çizelgesi</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Senetlerin, takas hakediş ödemelerinin ve gelecek dönem nakit akışlarının aylara göre kronolojik vade takvimi.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('promissory_notes.index') }}" class="btn btn-warning rounded-pill px-4 py-2.5 fw-extrabold text-dark shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="ri-table-line fs-5"></i>Senet Portföy Tablosu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-timeline h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM SENET PORTFÖYÜ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($totalAmount, 2, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-coins-line me-1"></i>Genel Portföy Hacmi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-wallet-3-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-timeline h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TAHSİL EDİLEN</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($paidAmount, 2, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Kasaya Giren Nakit</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-timeline h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">BEKLEYEN VADELER</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.6rem;">₺{{ number_format($pendingAmount, 2, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-time-line me-1"></i>Gelecek Dönem Akışı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-history-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-timeline h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">VADESİ GEÇEN UYARISI</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.6rem;">{{ $overdueCount }} Senet</div>
                        <small class="text-danger fw-bold"><i class="ri-alarm-warning-line me-1"></i>Gecikmiş Ödeme</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-alarm-warning-fill fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grouped Monthly Feed -->
    <div class="row">
        <div class="col-12">
            @forelse($groupedTimeline as $month => $notesGroup)
                <div class="card border shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
                    <div class="card-header bg-dark text-white py-3 px-4 d-flex align-items-center justify-content-between">
                        <h6 class="fw-extrabold text-white mb-0 d-flex align-items-center gap-2" style="font-size: 1rem;">
                            <i class="ri-calendar-check-fill text-warning"></i>{{ strtoupper($month) }} TAHSİLAT AKIŞI
                        </h6>
                        <span class="badge bg-warning text-dark font-monospace rounded-pill px-3 py-1 fw-extrabold">{{ count($notesGroup) }} İşlem</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-2.5">
                            @foreach($notesGroup as $n)
                                @php
                                    $isOverdue = $n->status === 'Bekliyor' && \Carbon\Carbon::parse($n->due_date)->isPast();
                                @endphp
                                <div class="timeline-item-card p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 {{ $isOverdue ? 'timeline-item-overdue' : '' }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px; background: {{ $isOverdue ? '#fee2e2' : '#fffbeb' }}; color: {{ $isOverdue ? '#dc2626' : '#d97706' }};">
                                            <i class="{{ $isOverdue ? 'ri-alarm-warning-fill' : 'ri-file-paper-2-fill' }} fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-extrabold text-dark mb-1" style="font-size: 1rem;">{{ $n->debtor_name ?? 'Müşteri / Alıcı' }}</h6>
                                            <small class="text-secondary fw-semibold">
                                                Senet No: <strong class="font-monospace text-dark">#{{ $n->note_number ?? $n->id }}</strong> | Vade Tarihi: <strong class="font-monospace text-primary">{{ \Carbon\Carbon::parse($n->due_date)->format('d.m.Y') }}</strong>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="text-md-end">
                                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.25rem;">₺{{ number_format($n->amount, 2, ',', '.') }}</div>
                                        <div>
                                            @if($isOverdue)
                                                <span class="badge text-white rounded-pill px-3 py-1 fw-bold shadow-sm" style="background: #dc2626; font-size: 11px;">
                                                    <i class="ri-alarm-warning-line me-1"></i>Vadesi Geçti (Gecikmiş)
                                                </span>
                                            @elseif($n->status === 'Ödendi' || $n->status === 'collected')
                                                <span class="badge text-white rounded-pill px-3 py-1 fw-bold shadow-sm" style="background: #16a34a; font-size: 11px;">
                                                    <i class="ri-checkbox-circle-line me-1"></i>Tahsil Edildi
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold shadow-sm" style="font-size: 11px;">
                                                    <i class="ri-time-line me-1"></i>Vadesi Bekliyor
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border">
                    <i class="ri-calendar-event-line text-secondary display-3 mb-3 d-block"></i>
                    <h5 class="fw-extrabold text-dark">Kayıtlı Vade Akışı Bulunmuyor</h5>
                    <p class="text-secondary mb-3">Senet listenize yeni kayıtlar ekleyerek aylık vade zaman çizelgesini takip edebilirsiniz.</p>
                    <a href="{{ route('promissory_notes.index') }}" class="btn btn-warning rounded-pill px-4 py-2.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-add-line me-1"></i>Yeni Senet Ekle
                    </a>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
