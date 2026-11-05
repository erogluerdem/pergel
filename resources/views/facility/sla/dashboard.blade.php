@extends('layouts.master')

@section('title', 'SLA Performans Panosu')
@section('topbar-title', 'Tesis Yönetimi - SLA Dashboard')

@section('content')
<div class="container-fluid">
    @php
        $projectModel = \App\Models\Project::find($projectId);
    @endphp

    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="fw-bold mb-1">
                <i class="ri-timer-flash-line text-primary me-2"></i>SLA Performans Panosu: {{ $projectModel->name ?? ('Proje #' . $projectId) }}
            </h4>
            <p class="text-muted mb-0">Servis seviyesi anlaşmaları (SLA) uyumluluk oranı, çözüm süreleri ve açık kritik uyarılar.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('facility.sla.alerts', $projectId) }}" class="btn btn-danger">
                <i class="ri-alarm-warning-line me-1"></i>SLA Uyarıları ({{ count($criticalAlerts ?? []) }})
            </a>
            <a href="{{ route('facility.sla.index') }}" class="btn btn-outline-secondary ms-1">
                <i class="ri-settings-3-line me-1"></i>SLA Ayarları
            </a>
        </div>
    </div>

    <!-- KPI Kartları -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-start border-success border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">SLA Uyum Oranı</small>
                    <h3 class="fw-bold text-success mt-1 mb-0">%{{ number_format($compliancePercentage ?? 0, 1) }}</h3>
                    <small class="text-muted">Uyumlu: {{ $stats['sla_compliant'] ?? 0 }} / İhlal: {{ $stats['sla_violated'] ?? 0 }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-primary border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Toplam Servis Talebi</small>
                    <h3 class="fw-bold mt-1 mb-0">{{ $stats['total_requests'] ?? 0 }}</h3>
                    <small class="text-muted">Tamamlanan: {{ $stats['completed_requests'] ?? 0 }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-info border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Ort. Çözüm Süresi</small>
                    <h3 class="fw-bold text-info mt-1 mb-0">
                        {{ round(($stats['average_resolution_time'] ?? 0) / 3600, 1) }} Saat
                    </h3>
                    <small class="text-muted">Aktif İşlemde: {{ $stats['in_progress_requests'] ?? 0 }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 border-start border-danger border-4">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold">Kritik / Uyarı Alarmları</small>
                    <h3 class="fw-bold text-danger mt-1 mb-0">
                        {{ $stats['critical_alerts'] ?? 0 }} / {{ $stats['warning_alerts'] ?? 0 }}
                    </h3>
                    <small class="text-muted">Bekleyen Talep: {{ $stats['pending_requests'] ?? 0 }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- SLA Konfigürasyonları -->
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tanımlı SLA Hedefleri</h5>
                    <a href="{{ route('facility.sla.create') }}" class="btn btn-sm btn-primary">
                        <i class="ri-add-line me-1"></i>Yeni Hedef
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Kategori</th>
                                    <th>Yanıt Süresi</th>
                                    <th>Çözüm Süresi</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slaConfigs ?? [] as $cfg)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $cfg->issue_category }}</td>
                                        <td>{{ round(($cfg->response_time ?? 0) / 3600, 1) }} Saat</td>
                                        <td>{{ round(($cfg->resolution_time ?? 0) / 3600, 1) }} Saat</td>
                                        <td>
                                            @if($cfg->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Pasif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Bu proje için tanımlı SLA hedefi bulunmuyor.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kritik Uyarılar -->
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-danger"><i class="ri-error-warning-line me-1"></i>Açık Kritik SLA Uyarıları</h5>
                    <span class="badge bg-danger">{{ count($criticalAlerts ?? []) }}</span>
                </div>
                <div class="card-body">
                    @forelse($criticalAlerts ?? [] as $alert)
                        <div class="p-3 mb-2 rounded border border-danger border-opacity-25 bg-danger bg-opacity-10">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong class="d-block">{{ $alert->serviceRequest->subject ?? ('Talep #' . $alert->service_request_id) }}</strong>
                                    <small class="text-muted">Konum: {{ $alert->serviceRequest->location ?? '-' }}</small>
                                </div>
                                <span class="badge bg-danger">KRİTİK</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="ri-checkbox-circle-line fs-2 text-success d-block mb-2"></i>
                            Açık kritik SLA ihlali bulunmamaktadır.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
