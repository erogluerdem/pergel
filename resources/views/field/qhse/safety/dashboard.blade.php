@extends('layouts.master')

@section('title', 'ISG Güvenlik Panosu')
@section('topbar-title', 'ISG')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">İş Sağlığı ve Güvenliği (İSG) Panosu</h4>
                <div class="page-title-right">
                    <!-- Aksiyon Butonu -->
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-plus"></i> Yeni İSG Kaydı Oluştur
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                            <li><a class="dropdown-item" href="{{ route('field.qhse.safety.incident.create') }}">
                                <i class="mdi mdi-alert-circle-outline"></i> Olay Bildir</a></li>
                            <li><a class="dropdown-item" href="{{ route('field.qhse.safety.permit.create') }}">
                                <i class="mdi mdi-file-certificate"></i> Çalışma İzni Başlat</a></li>
                            <li><a class="dropdown-item" href="{{ route('field.qhse.safety.toolbox.create') }}">
                                <i class="mdi mdi-school"></i> Eğitim Kaydet</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-success rounded">
                                <i class="mdi mdi-calendar-check"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Kayıp Zamansız Gün</p>
                            <h4 class="mb-0">{{ $daysLost }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-warning rounded">
                                <i class="mdi mdi-alert-circle"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Bu Ay Ramak Kala</p>
                            <h4 class="mb-0">{{ $ramakCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-danger rounded">
                                <i class="mdi mdi-wrench"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Açık Düzeltici Faaliyet</p>
                            <h4 class="mb-0">{{ $openCorrectiveActions }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-info rounded">
                                <i class="mdi mdi-file-certificate"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Aktif Çalışma İzni</p>
                            <h4 class="mb-0">{{ $activePermits }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Cards -->
    <div class="row">
        <div class="col-12">
            <h5 class="mb-3">İSG Yönetim Modülleri</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-title bg-primary rounded">
                                <i class="mdi mdi-alert-circle-outline"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Olay ve Kaza Raporlama</h5>
                            <p class="text-muted mb-2">Ramak kala ve kazaların anında bildirimi</p>
                            <a href="{{ route('field.qhse.safety.incidents.index') }}" class="btn btn-outline-primary btn-sm">Olayları Görüntüle</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-title bg-success rounded">
                                <i class="mdi mdi-file-certificate"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">İşe Başlama İzinleri</h5>
                            <p class="text-muted mb-2">Yüksek riskli işlerin güvenli yürütülmesi</p>
                            <a href="{{ route('field.qhse.safety.permits.index') }}" class="btn btn-outline-success btn-sm">İzinleri Görüntüle</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card h-100">
                <a href="{{ route('field.qhse.safety.toolbox.index') }}" class="text-decoration-none">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <span class="avatar-title bg-warning rounded">
                                    <i class="mdi mdi-school"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1 text-warning">Saha Güvenlik Eğitimleri</h5>
                                <p class="text-muted mb-2">Günlük toolbox talk kayıtları</p>
                                <small class="text-primary"><i class="mdi mdi-mouse-pointer"></i> Tıklayarak hızlı erişim</small>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('field.qhse.safety.toolbox.create') }}" class="btn btn-warning btn-sm">
                            <i class="mdi mdi-plus"></i> Yeni Eğitim
                        </a>
                        <a href="{{ route('field.qhse.safety.toolbox.index') }}" class="btn btn-outline-warning btn-sm">
                            <i class="mdi mdi-eye"></i> Tümünü Gör
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Incidents Widget -->
    @if($recentIncidents->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Son 7 Gündeki Olaylar</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Tarih</th>
                                    <th>Tip</th>
                                    <th>Lokasyon</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentIncidents as $incident)
                                <tr>
                                    <td>{{ $incident->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $incident->type === 'ramak_kala' ? 'warning' : 'danger' }}">
                                            {{ $incident->type === 'ramak_kala' ? 'Ramak Kala' : 'Kaza' }}
                                        </span>
                                    </td>
                                    <td><x-location-display :location="$incident->location" /></td>
                                    <td>
                                        <span class="badge bg-{{ $incident->status === 'open' ? 'danger' : 'success' }}">
                                            {{ $incident->status === 'open' ? 'Açık' : 'Kapalı' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
