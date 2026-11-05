@extends('layouts.master')

@section('title', 'Saha Raporu Detayı — ' . ($report->report_date ? $report->report_date->format('d.m.Y') : 'Rapor'))

@section('topbar-title', 'Saha Operasyonları - Rapor Detay')

@push('styles')
<style>
    .field-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #0f172a 0%, #047857 50%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .field-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .field-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .weather-badge-box {
        border-radius: 16px;
        padding: 1.25rem;
        background: linear-gradient(135deg, #fffbe6 0%, #fef3c7 100%);
        border: 1px solid #fde68a;
    }
</style>
@endpush

@section('content')

@php
    $weather = is_array($report->weather) ? $report->weather : [];
@endphp

<!-- Hero Banner -->
<div class="field-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('field.operations') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Saha Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-calendar-line me-1"></i>{{ $report->report_date ? $report->report_date->format('d.m.Y') : '-' }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Günlük Saha Raporu Detayı</h1>
            <p class="text-white-50 mb-0 fs-6">Proje: {{ $project->name ?? 'Genel Saha' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('field.operations.edit', $report->id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>Raporu Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Notes & Details -->
    <div class="col-lg-8">
        <!-- Notes Card -->
        <div class="field-glass-card">
            <h5 class="fw-bold text-dark mb-3"><i class="ri-file-text-line text-emerald me-2" style="color:#059669;"></i>Günün Notları & İlerleme Özeti</h5>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark mb-4" style="white-space: pre-line; min-height: 100px;">
                {{ $report->notes ?: 'Not kaydedilmedi.' }}
            </div>

            <!-- Labour Table -->
            <h5 class="fw-bold text-dark mb-3"><i class="ri-team-line text-primary me-2"></i>İş Gücü & Taşeron Puantajı</h5>
            <div class="table-responsive mb-4">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Taşeron Firma</th>
                            <th>Uzmanlık / İş Kolu</th>
                            <th>Personel Sayısı</th>
                            <th>Çalışma Saati</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report->labours as $labour)
                            <tr>
                                <td><strong class="text-dark">{{ $labour->subcontractor }}</strong></td>
                                <td><span class="badge bg-light text-dark border me-1">{{ $labour->trade }}</span></td>
                                <td><span class="fw-bold text-primary">{{ $labour->count }} Kişi</span></td>
                                <td><span class="text-muted">{{ $labour->hours }} Saat</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted text-center py-3">İş gücü kaydı bulunmuyor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Equipment Table -->
            <h5 class="fw-bold text-dark mb-3"><i class="ri-truck-line text-warning me-2"></i>Makine & Ekipman Parkı</h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ekipman Adı / Tipi</th>
                            <th>Plaka / Kod</th>
                            <th>Çalışma Saati</th>
                            <th>Rölanti / Duruş</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report->equipments as $eq)
                            <tr>
                                <td><strong class="text-dark">{{ $eq->equipment_type }}</strong></td>
                                <td><span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-2 py-1">{{ $eq->identifier }}</span></td>
                                <td><span class="fw-bold text-success">{{ $eq->working_hours }} Saat</span></td>
                                <td><span class="text-muted">{{ $eq->idle_hours }} Saat</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted text-center py-3">Ekipman kaydı bulunmuyor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Weather & Media -->
    <div class="col-lg-4">
        <!-- Weather Spec Box -->
        <div class="weather-badge-box mb-4">
            <h6 class="fw-bold text-dark mb-2"><i class="ri-sun-cloudy-line text-warning me-2 fs-5"></i>Hava Durumu Verisi</h6>
            @if(!empty($weather['temperature']))
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <div>
                        <h2 class="fw-extrabold text-dark mb-0">{{ $weather['temperature'] }}°C</h2>
                        <span class="text-muted small fw-bold text-uppercase">{{ $weather['description'] ?? 'Saha Hava Koşulu' }}</span>
                    </div>
                    <div class="text-end text-muted small">
                        <div>Nem: %{{ $weather['humidity'] ?? '-' }}</div>
                        <div>Rüzgar: {{ $weather['wind_speed'] ?? '-' }} km/s</div>
                    </div>
                </div>
            @else
                <p class="text-muted small mb-0">Hava durumu bilgisi girilmedi.</p>
            @endif
        </div>

        <!-- Field Media Gallery -->
        <div class="field-glass-card">
            <h6 class="fw-bold text-dark mb-3"><i class="ri-camera-line text-info me-2"></i>Saha Fotoğrafları ({{ $report->media->count() }})</h6>
            <div class="row g-2">
                @forelse($report->media as $media)
                    <div class="col-6">
                        <div class="border rounded-3 overflow-hidden bg-light text-center p-2">
                            <i class="ri-image-line fs-1 text-muted d-block my-2"></i>
                            <small class="text-dark d-block text-truncate fw-bold">{{ $media->original_name }}</small>
                            <span class="text-muted small">{{ round($media->size / 1024) }} KB</span>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-3">
                        Saha fotoğrafı yüklenmedi.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
