@extends('layouts.master')

@section('title', 'Tüm Personel & Mühendis Kadrosu')

@section('topbar-title', 'İK - Personel Listesi')

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
        margin-bottom: 2rem;
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

    .avatar-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(79, 70, 229, 0.1);
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }
</style>
@endpush

@section('content')

<!-- Hero Banner -->
<div class="hr-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('resources.hr.dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>İK Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Tüm Personel & Şantiye Kadrosu</h1>
            <p class="text-white-50 mb-0 fs-6">Proje mühendisleri, şantiye şefleri, mimarlar ve teknik personel rehberi.</p>
        </div>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="hr-glass-card mb-4 p-3">
    <form method="GET" action="{{ route('resources.hr.employees') }}">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" name="position" class="form-control" placeholder="Unvan veya pozisyon ara..." value="{{ request('position') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="department" class="form-select">
                    <option value="">Tüm Departmanlar</option>
                    <option value="Proje Yönetimi">Proje Yönetimi</option>
                    <option value="Saha Uygulama">Saha Uygulama</option>
                    <option value="MEP Tesisat">MEP Tesisat</option>
                    <option value="Elektrik & Otomasyon">Elektrik & Otomasyon</option>
                    <option value="Kalite Yönetimi">Kalite Yönetimi</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="project" class="form-select">
                    <option value="">Tüm Projeler</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-indigo text-white w-100 rounded-pill fw-bold" style="background:#4f46e5;">
                    <i class="ri-filter-3-line me-1"></i>Filtrele
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="hr-glass-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Personel Ad Soyad</th>
                    <th>Pozisyon / Unvan</th>
                    <th>Departman</th>
                    <th>Telefon & E-posta</th>
                    <th>Mevcut Projesi</th>
                    <th>İşe Giriş Tarihi</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle">
                                    {{ mb_substr($emp->first_name, 0, 1) }}{{ mb_substr($emp->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <strong class="text-dark d-block fs-6">{{ $emp->full_name }}</strong>
                                    <small class="text-muted">Sicil No: #EMP-{{ sprintf('%04d', $emp->id) }}</small>
                                </div>
                            </div>
                        </td>
                        <td><strong class="text-dark">{{ $emp->position }}</strong></td>
                        <td><span class="badge bg-indigo bg-opacity-10 text-indigo border" style="color:#4f46e5;">{{ $emp->department }}</span></td>
                        <td>
                            <small class="text-dark d-block"><i class="ri-phone-line me-1"></i>{{ $emp->phone }}</small>
                            <small class="text-muted"><i class="ri-mail-line me-1"></i>{{ $emp->email }}</small>
                        </td>
                        <td>
                            @if($emp->currentProject)
                                <span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1 fw-bold">
                                    <i class="ri-building-4-line me-1"></i>{{ $emp->currentProject->name }}
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border rounded-pill px-3 py-1 fw-bold">Merkez Havuz</span>
                            @endif
                        </td>
                        <td><span class="text-dark small">{{ $emp->hire_date ? $emp->hire_date->format('d.m.Y') : '-' }}</span></td>
                        <td class="text-end pe-4">
                            <a href="{{ route('resources.hr.employee_profile', $emp->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="ri-user-line me-1"></i>Profil Detayı
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Personel kaydı bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-opacity-10">
        <small class="text-muted">Toplam {{ $employees->total() }} personel gösteriliyor</small>
        <div>{{ $employees->links() }}</div>
    </div>
</div>

@endsection
