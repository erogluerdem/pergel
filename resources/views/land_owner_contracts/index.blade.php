@extends('layouts.master')

@section('title', 'Kat Karşılığı & Gelir Paylaşımı Yönetim Matrisi')

@push('styles')
<style>
    .contract-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #4338ca 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        box-shadow: 0 15px 35px rgba(30, 27, 75, 0.4) !important;
    }
    
    .contract-badge {
        background: rgba(99, 102, 241, 0.2) !important;
        border: 1px solid rgba(99, 102, 241, 0.4) !important;
        color: #c7d2fe !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-contract {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-contract {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-contract {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-contract:focus {
        background-color: #ffffff !important;
        border-color: #4338ca !important;
        box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.15) !important;
    }

    .contract-item-card {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        transition: all 0.3s ease !important;
    }
    .contract-item-card:hover {
        border-color: #4338ca !important;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 contract-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 contract-badge">
                            <i class="ri-shake-hands-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">ARSA SAHİBİ & KAT KARŞILIĞI SÖZLEŞMELERİ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Kat Karşılığı & Gelir Paylaşımı Yönetimi</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Arsa sahipleri ile imzalanan kat karşılığı inşaat sözleşmelerini, bağımsız bölüm (ünite) paylaşım matrisini ve aşama bazlı tapu devir şartlarını tek ekrandan yönetin.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('land_owner_contracts.ai_analysis') }}" class="btn btn-warning rounded-pill px-4 py-2.5 fw-extrabold text-dark shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="ri-robot-3-line fs-5"></i>AI Risk Analizörü
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ session('success') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $totalContractsCount = $contracts->total();
        $avgLandShare = $contracts->count() > 0 ? round($contracts->avg('land_share_percentage'), 1) : 50;
        
        $allMatrices = $contracts->pluck('unitShareMatrices')->flatten();
        $totalMatricesCount = $allMatrices->count();
        $transferredCount = $allMatrices->where('is_deed_transferred', true)->count();
    @endphp

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-contract h-100 border-start border-indigo border-4" style="border-left-color: #4338ca !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">AKTİF SÖZLEŞME SAYISI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $totalContractsCount }} Sözleşme</div>
                        <small class="text-primary fw-bold"><i class="ri-file-paper-2-line me-1"></i>Arsa Sahibi Protokolleri</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #e0e7ff !important; border: 1px solid #c7d2fe !important;">
                        <i class="ri-file-text-line fs-2" style="color: #4338ca !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-contract h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ORTALAMA ARSA SAHİBİ PAYI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">%{{ $avgLandShare }}</div>
                        <small class="text-warning fw-bold"><i class="ri-pie-chart-line me-1"></i>Paylaşım Oranı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-pie-chart-2-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-contract h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">EŞLEŞEN BAĞIMSIZ BÖLÜM</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $totalMatricesCount }} Ünite</div>
                        <small class="text-info fw-bold"><i class="ri-building-4-line me-1"></i>Paylaşım Matrisi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-layout-grid-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-contract h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TAPU DEVRİ TAMAMLANAN</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $transferredCount }} Ünite</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Devredilen Tapular</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-key-2-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- New Contract Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-contract shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #e0e7ff; color: #4338ca;">
                        <i class="ri-add-circle-line fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni Kat Karşılığı Sözleşmesi</h5>
                </div>

                <form action="{{ route('land_owner_contracts.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">PROJE SEÇİNİZ</label>
                        <select name="project_id" class="form-select rounded-pill custom-input-contract" required>
                            <option value="">Proje Seçin...</option>
                            @foreach($projects as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ARSA SAHİBİ ADI / UNVANI</label>
                        <input type="text" name="land_owner_name" class="form-control rounded-pill custom-input-contract" placeholder="Örn: Ahmet Yılmaz veya Yılmaz İnşaat A.Ş." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ARSA SAHİBİ T.C. / VKN</label>
                        <input type="text" name="land_owner_tc_vkn" class="form-control rounded-pill custom-input-contract" placeholder="11 Haneli T.C. veya 10 Haneli VKN">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">ARSA SAHİBİ PAYI (%)</label>
                            <input type="number" step="0.1" name="land_share_percentage" class="form-control rounded-pill custom-input-contract text-warning" value="50" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">MÜTEAHHİT PAYI (%)</label>
                            <input type="number" step="0.1" name="contractor_share_percentage" class="form-control rounded-pill custom-input-contract text-primary" value="50" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">TAPU DEVİR ŞARTLARI & AŞAMALARI</label>
                        <textarea name="deed_transfer_terms" class="form-control rounded-4 custom-input-contract" rows="3" placeholder="Örn: Su basmanda %10 devir, Kaba inşaat %40 devir, İskanda %100 devir..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-extrabold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #4338ca; border-color: #4338ca;">
                        <i class="ri-save-3-fill fs-5"></i>Sözleşmeyi Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Contracts & Unit Share Matrices List Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 bg-white p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                    <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="ri-building-fill text-indigo fs-4" style="color: #4338ca;"></i>Sözleşmeler & Ünite Paylaşım Matrisleri
                    </h5>
                    <span class="badge bg-indigo text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #4338ca;">{{ $contracts->total() }} Kayıt</span>
                </div>

                @forelse($contracts as $contract)
                    <div class="contract-item-card p-4 mb-4">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
                            <div>
                                <span class="badge bg-dark bg-opacity-75 text-warning font-monospace px-2.5 py-1 rounded-pill mb-1">Sözleşme #{{ $contract->id }}</span>
                                <h5 class="fw-extrabold text-dark mb-0">
                                    <i class="ri-home-4-line text-primary me-1"></i>{{ $contract->project->name ?? 'Proje' }}
                                    <span class="text-secondary font-weight-normal fs-6">| {{ $contract->land_owner_name }}</span>
                                </h5>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold">Arsa Sahibi: %{{ $contract->land_share_percentage }}</span>
                                <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 fw-bold">Müteahhit: %{{ $contract->contractor_share_percentage }}</span>
                            </div>
                        </div>

                        <p class="text-secondary small mb-3 p-2.5 bg-light rounded-3 border">
                            <strong class="text-dark"><i class="ri-file-text-line me-1"></i>Tapu Devir Şartları:</strong> {{ $contract->deed_transfer_terms ?? 'Belirtilmedi' }}
                        </p>

                        <!-- Unit Share Matrix Table -->
                        <h6 class="fw-extrabold text-dark mb-2 d-flex align-items-center gap-1" style="font-size: 13px;">
                            <i class="ri-layout-grid-fill text-primary"></i>Ünite Paylaşım Matrisi
                        </h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead class="bg-light text-dark">
                                    <tr>
                                        <th class="ps-3 py-2 fw-bold">Ünite (Daire/Dükkan)</th>
                                        <th class="py-2 fw-bold">Mülkiyet Sahibi</th>
                                        <th class="py-2 fw-bold">Gerekli Aşama</th>
                                        <th class="py-2 fw-bold">Tapu Devri</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contract->unitShareMatrices as $matrix)
                                        <tr>
                                            <td class="ps-3 fw-bold text-dark">{{ $matrix->unit->name ?? 'Ünite #' . $matrix->unit_id }}</td>
                                            <td>
                                                @if($matrix->owner_type === 'land_owner')
                                                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fw-bold"><i class="ri-user-line me-1"></i>Arsa Sahibi</span>
                                                @elseif($matrix->owner_type === 'contractor')
                                                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 fw-bold"><i class="ri-hard-hat-line me-1"></i>Müteahhit</span>
                                                @else
                                                    <span class="badge bg-secondary text-white rounded-pill px-2.5 py-1 fw-bold"><i class="ri-team-line me-1"></i>Ortak</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-light text-dark border rounded-pill px-2.5 py-1 font-monospace fw-bold">{{ strtoupper($matrix->stage_requirement) }}</span></td>
                                            <td>
                                                @if($matrix->is_deed_transferred)
                                                    <span class="badge bg-success text-white rounded-pill px-2.5 py-1 fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Devredildi</span>
                                                @else
                                                    <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 fw-bold"><i class="ri-time-line me-1"></i>Devredilmedi</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3 small">Bu sözleşmeye bağlı henüz ünite paylaşım kaydı eklenmedi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Add Unit Matrix Form -->
                        <form action="{{ route('land_owner_contracts.add_matrix', $contract->id) }}" method="POST" class="row g-2 align-items-center p-2 rounded-3 border" style="background: #f8fafc;">
                            @csrf
                            <div class="col-md-3">
                                <select name="unit_id" class="form-select form-select-sm rounded-pill custom-input-contract" required>
                                    <option value="">Ünite Seç...</option>
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}">{{ $u->name ?? 'Daire '.$u->id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="owner_type" class="form-select form-select-sm rounded-pill custom-input-contract" required>
                                    <option value="land_owner">Arsa Sahibi</option>
                                    <option value="contractor" selected>Müteahhit</option>
                                    <option value="shared">Ortak</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="stage_requirement" class="form-select form-select-sm rounded-pill custom-input-contract" required>
                                    <option value="substructure">Su Basman (%10)</option>
                                    <option value="superstructure">Kaba İnşaat (%40)</option>
                                    <option value="finishing">İnce İnşaat (%70)</option>
                                    <option value="occupancy_permit" selected>İskan (%100)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill w-100 fw-bold"><i class="ri-add-line me-1"></i>Ünite Ekle</button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="ri-shake-hands-line display-3 text-secondary mb-2 d-block"></i>
                        <h6 class="fw-bold text-dark">Henüz Sözleşme Kaydı Bulunmuyor</h6>
                        <p class="small text-muted">Sol taraftaki formdan projenizi ve arsa sahibi bilgilerini girerek ilk kat karşılığı sözleşmenizi tanımlayabilirsiniz.</p>
                    </div>
                @endforelse

                <div class="mt-3">
                    {{ $contracts->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
