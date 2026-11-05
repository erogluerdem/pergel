@extends('layouts.master')

@section('title', 'Dijital Sözleşmeler & E-İmza Yönetimi')

@push('styles')
<style>
    .dc-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .dc-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: dcGlow 4s linear infinite;
    }

    @keyframes dcGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .dc-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-dc {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-dc:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-card-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
    }

    .custom-dc-table thead,
    .custom-dc-table thead tr,
    .custom-dc-table thead th {
        background-color: #0f172a !important;
        color: #ffffff !important;
        padding: 16px !important;
        font-weight: 800 !important;
        font-size: 0.85rem !important;
        border-bottom: 2px solid #d97706 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        --bs-table-bg: #0f172a !important;
        --bs-table-color: #ffffff !important;
    }

    .custom-dc-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 600 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="dc-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill dc-badge-pill">
                        <i class="ri-shield-keyhole-line text-warning fs-5"></i> HUKUK & İDARİ - DİJİTAL SÖZLEŞMELER
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $contracts->total() }} Dijital Kayıt
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Dijital Sözleşmeler & E-İmza Yönetim Portalı</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Gayrimenkul satış, kiralama ve taşeron sözleşmelerinizi dijital olarak oluşturun, hukuk uyum sürecini takip edin ve e-imzalayın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('digital-contracts.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="ri-add-circle-fill fs-5"></i>+ Yeni Sözleşme Oluştur
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ $message }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-dc h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM SÖZLEŞME</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $contracts->total() }} Kayıt</div>
                        <small class="text-primary fw-bold"><i class="ri-file-text-line me-1"></i>Hukuki Evrak Portföyü</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-file-seal-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-dc h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">AKTİF & YÜRÜRLÜKTE</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ \App\Models\Contract::where('status', 'active')->count() }} Kayıt</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Yasal Geçiçi İmzalı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-dc h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TASLAK / ONAYDA</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ \App\Models\Contract::where('status', 'draft')->count() }} Kayıt</div>
                        <small class="text-warning fw-bold"><i class="ri-draft-line me-1"></i>E-İmza Bekleyenler</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-draft-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-dc h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM SÖZLEŞME HACMİ</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.5rem;">₺{{ number_format(\App\Models\Contract::sum('value'), 0, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Portföy Hukuki Hacmi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-file-list-3-line text-warning fs-5"></i>Dijital Sözleşmeler Cetveli
            </h5>
            <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                {{ $contracts->total() }} Kayıt
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-dc-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Sözleşme Kodu & Başlığı</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Proje / Şirket</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Sözleşme Tutarı</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Yürürlük Tarihi</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Durum</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $contract)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center text-warning" style="width: 40px; height: 40px; background: #fffbeb; border: 1px solid #fde68a;">
                                            <i class="ri-file-seal-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('digital-contracts.show', $contract) }}" class="fw-extrabold text-dark fs-6 text-decoration-none">
                                                {{ $contract->title ?? $contract->name }}
                                            </a>
                                            <small class="text-muted d-block font-monospace fw-bold">{{ $contract->number ?? ('DC-' . str_pad($contract->id, 4, '0', STR_PAD_LEFT)) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-extrabold text-dark d-block">🏗️ {{ $contract->project?->name ?? 'Proje Bağlanmadı' }}</span>
                                    <small class="text-muted fw-semibold">{{ $contract->company ?? 'Şirket Belirtilmedi' }}</small>
                                </td>
                                <td>
                                    <strong class="text-success font-monospace fs-6">₺{{ number_format($contract->value ?? 0, 0, ',', '.') }}</strong>
                                </td>
                                <td class="font-monospace text-dark fw-bold">
                                    @if($contract->effective_date)
                                        📅 {{ \Carbon\Carbon::parse($contract->effective_date)->format('d.m.Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center font-monospace">
                                    @if($contract->status == 'active')
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">🟢 AKTİF</span>
                                    @elseif($contract->status == 'draft')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">🟡 TASLAK</span>
                                    @elseif($contract->status == 'expired')
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">⚪ SÜRESİ DOLDU</span>
                                    @else
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #ef4444 !important;">🔴 {{ strtoupper($contract->status) }}</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('digital-contracts.show', $contract) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="İncele">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('digital-contracts.edit', $contract) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('digital-contracts.destroy', $contract) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'Dijital Sözleşmeyi Sil?', 'Bu sözleşme kaydı kalıcı olarak silinecektir.')" title="Sil">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="ri-file-warning-line display-4 text-warning mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Henüz Dijital Sözleşme Bulunmuyor</h6>
                                    <p class="small text-muted mb-0">Üstteki "+ Yeni Sözleşme Oluştur" butonuna tıklayarak ilk sözleşmenizi tanımlayabilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-3 d-flex justify-content-center">
            {{ $contracts->links() }}
        </div>
    </div>

</div>
@endsection
