@extends('subcontractor-portal.layouts.app', ['title' => 'Gösterge Paneli'])

@section('content')
<div class="card portal-hero mb-4 border-0 overflow-hidden text-white">
    <div class="card-body p-4 p-lg-5 position-relative">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7">
                <span class="badge hero-badge text-uppercase mb-3">Günlük saha özeti</span>
                <h1 class="display-6 fw-bold mb-3">Merhaba {{ auth('subcontractor')->user()->name }} 👋</h1>
                <p class="lead text-white-70 mb-4">Hizmet kayıtlarınızı, malzeme teslimlerini ve sözleşme performansınızı buradan tek ekranda yönetin.</p>
                <div class="d-flex flex-wrap gap-2 gap-lg-3">
                    <a href="{{ route('subcontractor.services.create') }}" class="btn btn-gradient d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        Yeni hizmet kaydı
                    </a>
                    <a href="{{ route('subcontractor.materials.create') }}" class="btn btn-secondary-soft d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-box-open"></i>
                        Malzeme teslimi ekle
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-graphic mx-auto">
                    <div class="graphic-core">
                        <span class="spark spark-1"></span>
                        <span class="spark spark-2"></span>
                        <span class="spark spark-3"></span>
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div class="hero-metric">
                        <span class="label">Son 7 gün</span>
                        <span class="value">{{ $serviceStats['submitted'] + $materialStats['submitted'] }} kayıt</span>
                    </div>
                    <div class="hero-metric metric-alt">
                        <span class="label">Onaylanan</span>
                        <span class="value">{{ $serviceStats['approved'] + $materialStats['approved'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100">
            <div class="stat-icon icon-primary">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="text-uppercase small text-muted fw-semibold">Toplam Hizmet Kaydı</div>
            <div class="display-6 fw-bold mb-1">{{ $serviceStats['total'] }}</div>
            <div class="stat-foot text-success small">
                <i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $serviceStats['submitted'] }} gönderildi
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100">
            <div class="stat-icon icon-success">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="text-uppercase small text-muted fw-semibold">Onaylanan Hizmet</div>
            <div class="display-6 fw-bold mb-1">{{ $serviceStats['approved'] }}</div>
            <div class="stat-foot text-muted small">Taslak: {{ $serviceStats['draft'] }}</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100">
            <div class="stat-icon icon-accent">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div class="text-uppercase small text-muted fw-semibold">Malzeme Teslimi</div>
            <div class="display-6 fw-bold mb-1">{{ $materialStats['total'] }}</div>
            <div class="stat-foot text-accent small"><i class="fa-solid fa-paper-plane me-1"></i>{{ $materialStats['submitted'] }} gönderildi</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card h-100">
            <div class="stat-icon icon-warning">
                <i class="fa-solid fa-badge-check"></i>
            </div>
            <div class="text-uppercase small text-muted fw-semibold">Onaylanan Malzeme</div>
            <div class="display-6 fw-bold mb-1">{{ $materialStats['approved'] }}</div>
            <div class="stat-foot text-muted small">Taslak: {{ $materialStats['draft'] }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Son Hizmet Kayıtları</h5>
                <a href="{{ route('subcontractor.services.index') }}" class="btn btn-sm btn-outline-primary">Tümü</a>
            </div>
            <div class="card-body">
                @forelse ($recentServiceEntries as $entry)
                    <div class="activity-item">
                        <div class="activity-marker">
                            <span class="dot"></span>
                        </div>
                        <div class="activity-content">
                            <div class="d-flex flex-wrap justify-content-between gap-2">
                                <div>
                                    <div class="fw-semibold">{{ $entry->service_type }}</div>
                                    <div class="text-muted small">{{ optional($entry->project)->name ?? 'Proje atanmamış' }}</div>
                                </div>
                                @include('subcontractor-portal.partials.status-badge', ['status' => $entry->status])
                            </div>
                            <div class="activity-meta text-muted small mt-2">
                                <i class="fa-regular fa-calendar me-1"></i>{{ $entry->entry_date?->format('d.m.Y') }}
                                @if($entry->worked_hours)
                                    <span class="ms-3"><i class="fa-regular fa-clock me-1"></i>{{ number_format($entry->worked_hours, 2, ',', '.') }} saat</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fa-solid fa-person-digging"></i>
                        <p>Henüz hizmet kaydı oluşturmadınız.</p>
                        <a href="{{ route('subcontractor.services.create') }}" class="btn btn-sm btn-gradient">İlk kaydı ekle</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card h-100 mb-4 mb-lg-0">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Aktif Sözleşmeleriniz</h5>
            </div>
            <div class="card-body">
                @forelse ($contracts as $contract)
                    <div class="contract-item">
                        @php
                            $statusClass = match($contract->status) {
                                'active' => 'status-submitted',
                                'completed' => 'status-approved',
                                'terminated' => 'status-rejected',
                                'draft' => 'status-draft',
                                default => 'status-default',
                            };
                            $progress = min(max($contract->progress_percentage ?? 0, 0), 100);
                        @endphp
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="fw-semibold">{{ $contract->contract_name }}</div>
                                <div class="text-muted small">{{ $contract->contract_number }} · {{ optional($contract->project)->name ?? 'Proje atanmamış' }}</div>
                            </div>
                            <span class="badge badge-status {{ $statusClass }} text-uppercase">{{ ucfirst($contract->status) }}</span>
                        </div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Henüz atandığınız sözleşme bulunmuyor.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Son Malzeme Teslimleri</h5>
        <a href="{{ route('subcontractor.materials.index') }}" class="btn btn-sm btn-gradient">Tümü</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th scope="col">Malzeme</th>
                    <th scope="col">Proje</th>
                    <th scope="col">Miktar</th>
                    <th scope="col">Durum</th>
                    <th scope="col">Tarih</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($recentMaterialEntries as $entry)
                    <tr>
                        <td class="fw-semibold">{{ $entry->material_name }}</td>
                        <td>{{ optional($entry->project)->name ?? 'Proje atanmamış' }}</td>
                        <td>{{ number_format($entry->quantity, 2, ',', '.') }} {{ $entry->unit }}</td>
                        <td>@include('subcontractor-portal.partials.status-badge', ['status' => $entry->status])</td>
                        <td>{{ $entry->delivery_date?->format('d.m.Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted text-center py-4">
                            <div class="empty-state">
                                <i class="fa-solid fa-truck"></i>
                                <p>Henüz malzeme teslim kaydı oluşturmadınız.</p>
                                <a href="{{ route('subcontractor.materials.create') }}" class="btn btn-sm btn-gradient">Teslim ekle</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
