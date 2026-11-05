@extends('layouts.master')

@section('title', 'Personel Profili — ' . $employee->full_name)

@section('topbar-title', 'İK - Personel Profili')

@push('styles')
<style>
    .hr-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #4f46e5 50%, #6366f1 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .hr-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .avatar-large {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 2rem;
        border: 3px solid rgba(255, 255, 255, 0.4);
    }
</style>
@endpush

@section('content')

<!-- Hero Banner -->
<div class="hr-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-large">
                {{ mb_substr($employee->first_name, 0, 1) }}{{ mb_substr($employee->last_name, 0, 1) }}
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <a href="{{ route('resources.hr.employees') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                        <i class="ri-arrow-left-line me-1"></i>Personel Listesi
                    </a>
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                        Sicil #EMP-{{ sprintf('%04d', $employee->id) }}
                    </span>
                </div>
                <h1 class="fw-extrabold text-white mb-1">{{ $employee->full_name }}</h1>
                <p class="text-white-50 mb-0 fs-6">{{ $employee->position }} — {{ $employee->department }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Personal Info & Contact -->
    <div class="col-lg-4">
        <div class="hr-glass-card">
            <h6 class="fw-bold text-dark mb-3"><i class="ri-contacts-line text-indigo me-2" style="color:#4f46e5;"></i>Özlük & İletişim Bilgileri</h6>
            <div class="list-group list-group-flush border-0">
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Telefon:</span>
                    <strong class="text-dark">{{ $employee->phone }}</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">E-Posta:</span>
                    <strong class="text-dark">{{ $employee->email }}</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">İşe Giriş Tarihi:</span>
                    <strong class="text-dark">{{ $employee->hire_date ? $employee->hire_date->format('d.m.Y') : '-' }}</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Çalışma Durumu:</span>
                    <span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1 fw-bold">Aktif Kadro</span>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Mevcut Şantiyesi:</span>
                    <strong class="text-indigo" style="color:#4f46e5;">{{ $employee->currentProject->name ?? 'Merkez Havuz' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Certificates & Project History -->
    <div class="col-lg-8">
        <!-- Certificates Card -->
        <div class="hr-glass-card">
            <h5 class="fw-bold text-dark mb-3">
                <i class="ri-award-line text-warning me-2"></i>Mesleki Sertifikalar & İSG Yetki Belgeleri
            </h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sertifika / Belge Adı</th>
                            <th>Belge No</th>
                            <th>Veren Kurum</th>
                            <th>Geçerlilik Tarihi</th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->certificates as $cert)
                            <tr>
                                <td><strong class="text-dark">{{ $cert->certificate_type }}</strong></td>
                                <td><span class="badge bg-dark bg-opacity-10 text-dark font-monospace">{{ $cert->certificate_number }}</span></td>
                                <td>{{ $cert->issuing_authority }}</td>
                                <td>
                                    <span class="fw-bold {{ $cert->isExpiringSoon() ? 'text-danger' : 'text-dark' }}">
                                        {{ $cert->expiry_date ? $cert->expiry_date->format('d.m.Y') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($cert->isExpired())
                                        <span class="badge bg-danger bg-opacity-10 text-danger border rounded-pill px-3 py-1 fw-bold">Süresi Doldu</span>
                                    @elseif($cert->isExpiringSoon())
                                        <span class="badge bg-warning bg-opacity-10 text-warning border rounded-pill px-3 py-1 fw-bold">Yenilenmeli</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1 fw-bold">Geçerli</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Kayıtlı sertifika bulunmamaktadır.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Assignments History -->
        <div class="hr-glass-card">
            <h5 class="fw-bold text-dark mb-3">
                <i class="ri-building-line text-indigo me-2" style="color:#4f46e5;"></i>Şantiye & Proje Görev Geçmişi
            </h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Proje Adı</th>
                            <th>Görev / Rol</th>
                            <th>Başlangıç Tarihi</th>
                            <th>Bitiş Tarihi</th>
                            <th>Atama Durumu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->assignments as $asg)
                            <tr>
                                <td><strong class="text-dark">{{ $asg->project->name ?? 'Proje' }}</strong></td>
                                <td>{{ $asg->role ?: $employee->position }}</td>
                                <td>{{ $asg->start_date ? $asg->start_date->format('d.m.Y') : '-' }}</td>
                                <td>{{ $asg->end_date ? $asg->end_date->format('d.m.Y') : 'Devam Ediyor' }}</td>
                                <td>
                                    @if($asg->status === 'active')
                                        <span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1 fw-bold">Aktif Görevde</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-3 py-1">Tamamlandı</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Görev kaydı bulunmamaktadır.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
