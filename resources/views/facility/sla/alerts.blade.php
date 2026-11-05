@extends('layouts.master')

@section('title', 'SLA Uyarıları ve İhlal Bildirimleri')
@section('topbar-title', 'Tesis Yönetimi - SLA Uyarıları')

@section('content')
<div class="container-fluid">
    @php
        $projectModel = \App\Models\Project::find($projectId);
    @endphp

    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="fw-bold mb-1">
                <i class="ri-alarm-warning-line text-danger me-2"></i>SLA Uyarıları: {{ $projectModel->name ?? ('Proje #' . $projectId) }}
            </h4>
            <p class="text-muted mb-0">Kritik süre aşımı ve yaklaşan SLA hedef uyarılarını inceleyin ve onaylayın.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('facility.sla.dashboard', $projectId) }}" class="btn btn-outline-primary">
                <i class="ri-dashboard-line me-1"></i>SLA Panosuna Dön
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kritik Uyarılar -->
        <div class="col-lg-6">
            <div class="card h-100 border-danger">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-white mb-0">
                        <i class="ri-fire-line me-1"></i>Kritik SLA İhlalleri (%100 Süre Aşımı)
                    </h5>
                    <span class="badge bg-white text-danger">{{ count($criticalAlerts ?? []) }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Servis Talebi</th>
                                    <th>Konum</th>
                                    <th>Tetiklenme</th>
                                    <th class="text-end pe-3">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($criticalAlerts ?? [] as $alert)
                                    <tr id="alert-row-{{ $alert->id }}">
                                        <td class="ps-3 fw-semibold">{{ $alert->serviceRequest->subject ?? ('Talep #' . $alert->service_request_id) }}</td>
                                        <td>{{ $alert->serviceRequest->location ?? '-' }}</td>
                                        <td><small class="text-muted">{{ $alert->triggered_at ? \Carbon\Carbon::parse($alert->triggered_at)->format('d.m.Y H:i') : '-' }}</small></td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="acknowledgeAlert({{ $alert->id }})">
                                                <i class="ri-check-line me-1"></i>Onayla
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Açık kritik SLA uyarısı yok.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Uyarı Seviyesi Alarmlar -->
        <div class="col-lg-6">
            <div class="card h-100 border-warning">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="card-title text-dark mb-0">
                        <i class="ri-alert-line me-1"></i>Yaklaşan SLA Uyarıları (%75 Süre Eşiği)
                    </h5>
                    <span class="badge bg-dark text-white">{{ count($warningAlerts ?? []) }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Servis Talebi</th>
                                    <th>Konum</th>
                                    <th>Tetiklenme</th>
                                    <th class="text-end pe-3">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warningAlerts ?? [] as $alert)
                                    <tr id="alert-row-{{ $alert->id }}">
                                        <td class="ps-3 fw-semibold">{{ $alert->serviceRequest->subject ?? ('Talep #' . $alert->service_request_id) }}</td>
                                        <td>{{ $alert->serviceRequest->location ?? '-' }}</td>
                                        <td><small class="text-muted">{{ $alert->triggered_at ? \Carbon\Carbon::parse($alert->triggered_at)->format('d.m.Y H:i') : '-' }}</small></td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="acknowledgeAlert({{ $alert->id }})">
                                                <i class="ri-check-line me-1"></i>Onayla
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Açık yaklaşan SLA uyarısı yok.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function acknowledgeAlert(alertId) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    fetch('/facility/sla/alerts/' + alertId + '/acknowledge', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({ notes: 'Onaylandı' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('alert-row-' + alertId);
            if (row) row.remove();
        }
    })
    .catch(err => console.error(err));
}
</script>
@endsection
