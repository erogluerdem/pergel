@extends('layouts.master')

@section('title', 'Proje & Şantiye Kalite Standartları Portalı')

@section('topbar-title', 'Yönetim - Kalite Standartları')

@push('styles')
<style>
    /* Hero Header Banner */
    .std-adm-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .std-adm-hero-banner,
    :root:not([data-bs-theme="dark"]) .std-adm-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #0d9488 50%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .std-adm-hero-banner {
        background: linear-gradient(135deg, rgba(13, 148, 136, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .std-adm-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .std-adm-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .std-adm-card,
    :root:not([data-bs-theme="dark"]) .std-adm-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .std-adm-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .std-adm-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Table Container */
    .std-adm-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .std-adm-glass-card,
    :root:not([data-bs-theme="dark"]) .std-adm-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .std-adm-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Flash Notifications -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
        <i class="ri-checkbox-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-pill px-4" role="alert">
        <i class="ri-error-warning-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Hero Header Banner -->
<div class="std-adm-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-award-line me-1"></i>Quality Standards & Regulatory Compliance
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Şantiye & Proje Kalite Standartları</h1>
            <p class="text-white-50 mb-0 fs-6">ISO, TS EN, ASTM standartları, kalite şartnameleri ve teknik dokümantasyon kütüphanesi.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.standards.export_csv') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold">
                <i class="ri-download-2-line me-1"></i>CSV İndir
            </a>
            <a href="{{ route('admin.standards.create') }}" class="btn btn-light rounded-pill px-4 fw-bold text-teal shadow-sm" style="color:#0d9488;">
                <i class="ri-add-circle-line me-1"></i>Yeni Standart Ekle
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="std-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Standart Dokümanı</span>
                    <h2 class="fw-extrabold mb-0 text-teal" style="color:#0d9488;">{{ number_format($stats['total'] ?? 0) }} Standart</h2>
                </div>
                <div class="std-adm-stat-icon bg-teal bg-opacity-10 text-teal" style="color:#0d9488;">
                    <i class="ri-book-read-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="std-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Aktif Kullanımdaki Standartlar</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ number_format($stats['active'] ?? 0) }} Aktif</h2>
                </div>
                <div class="std-adm-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="std-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Kritik Öncelikli Şartnameler</span>
                    <h2 class="fw-extrabold mb-0 text-danger">{{ number_format($stats['critical'] ?? 0) }} Kritik</h2>
                </div>
                <div class="std-adm-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-alert-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="std-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Taslak / Onay Bekleyen</span>
                    <h2 class="fw-extrabold mb-0 text-warning">{{ number_format($stats['draft'] ?? 0) }} Taslak</h2>
                </div>
                <div class="std-adm-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-edit-box-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="std-adm-glass-card p-3 mb-4">
    <form method="GET" action="{{ route('admin.standards.index') }}" class="row g-2 align-items-center">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Arama Yap</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ri-search-line"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Kod, ad veya açıklama..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Kategori</label>
            <select name="category" class="form-select">
                <option value="">-- Tüm Kategoriler --</option>
                @foreach(\App\Models\Standard::CATEGORIES as $key => $label)
                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Standart Türü</label>
            <select name="type" class="form-select">
                <option value="">-- Tüm Türler --</option>
                @foreach(\App\Models\Standard::TYPES as $key => $label)
                    <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end pt-4">
            <button type="submit" class="btn btn-teal text-white w-100 rounded-pill fw-bold" style="background:#0d9488;">
                <i class="ri-filter-3-line me-1"></i>Standartları Filtrele
            </button>
        </div>
    </form>
</div>

<!-- Main Standards Table -->
<div class="std-adm-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-file-list-3-line text-teal me-2" style="color:#0d9488;"></i>Kalite Standartları Cetveli
        </h5>
        <span class="badge bg-teal bg-opacity-10 border border-teal border-opacity-30 rounded-pill px-3 py-1 fw-bold text-teal" style="color:#0d9488;">
            Toplam {{ $standards->total() }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Kod & Versiyon</th>
                    <th>Standart Adı & Tanımı</th>
                    <th>Kategori / Tür</th>
                    <th>Zorunluluk Seviyesi</th>
                    <th>Durum</th>
                    <th>Yürürlük Tarihi</th>
                    <th class="text-end pe-4">Aksiyonlar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($standards as $std)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-teal bg-opacity-10 text-teal border border-teal border-opacity-30 rounded-pill px-3 py-1 font-monospace fw-bold" style="color:#0d9488;">
                                {{ $std->code }}
                            </span>
                            <small class="d-block text-muted mt-1">{{ $std->version ?? 'v1.0' }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.standards.show', $std->id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                {{ $std->name }}
                            </a>
                            <small class="text-muted d-block text-truncate" style="max-width: 320px;">{{ $std->description }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-dark border rounded-pill px-3 py-1 me-1">
                                {{ \App\Models\Standard::CATEGORIES[$std->category] ?? $std->category }}
                            </span>
                        </td>
                        <td>
                            @if($std->compliance_level === 'mandatory')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                                    <i class="ri-shield-keyhole-line me-1"></i>Zorunlu
                                </span>
                            @else
                                <span class="badge bg-info bg-opacity-10 text-info border rounded-pill px-3 py-1">
                                    {{ \App\Models\Standard::COMPLIANCE_LEVELS[$std->compliance_level] ?? $std->compliance_level }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $std->status_color }} bg-opacity-10 text-{{ $std->status_color }} border rounded-pill px-3 py-1">
                                {{ \App\Models\Standard::STATUSES[$std->status] ?? $std->status }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $std->effective_date ? \Carbon\Carbon::parse($std->effective_date)->format('d.m.Y') : '-' }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.standards.show', $std->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3 me-1" title="Detay">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('admin.standards.edit', $std->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 me-1" title="Düzenle">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.standards.destroy', $std->id) }}" class="d-inline" onsubmit="return confirm('Bu kalite standardını silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Sil">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ri-folder-unknow-line fs-1 d-block mb-2 text-secondary"></i>
                            Kayıtlı kalite standardı bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="d-flex justify-content-end mt-4">
        {{ $standards->links() }}
    </div>
</div>

@endsection