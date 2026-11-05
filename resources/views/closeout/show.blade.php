@extends('layouts.master')

@section('title', 'Proje Kapanış Detayları — ' . ($closeout->project->name ?? 'Proje'))

@section('topbar-title', 'Proje Yönetimi - Kapanış Detay')

@push('styles')
<style>
    /* ROYAL DARK SLATE & INDIGO THEME */
    .closeout-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #4338ca 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(99, 102, 241, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .closeout-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #3b82f6, #10b981, #6366f1);
        background-size: 300% 100%;
        animation: closeoutGlow 4s linear infinite;
    }

    @keyframes closeoutGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    /* KPI Spec Boxes */
    .closeout-spec-box {
        border-radius: 16px !important;
        padding: 1.5rem !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04) !important;
        height: 100%;
        transition: transform 0.2s ease;
    }

    .closeout-spec-box:hover {
        transform: translateY(-2px);
    }

    /* Container Card */
    .closeout-glass-card {
        border-radius: 20px !important;
        padding: 1.5rem !important;
        margin-bottom: 2rem;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
    }

    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #475569;
        font-weight: 800;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s ease;
    }

    .nav-tabs .nav-link.active {
        color: #4338ca;
        border-bottom-color: #4338ca;
        background: transparent;
    }

    .closeout-table thead th {
        background: #0f172a !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 20px !important;
        border: none !important;
    }

    .closeout-table tbody td {
        padding: 16px 20px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4">
    <!-- Hero Header Banner -->
    <div class="closeout-hero-banner text-white mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('closeout.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold">
                        <i class="ri-arrow-left-line me-1"></i>Kapanış Panosuna Dön
                    </a>
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 fw-bold font-monospace">
                        <i class="ri-building-4-line me-1 text-warning"></i>{{ $closeout->project->name ?? 'Proje Belirtilmedi' }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-1">Proje Kapanış & Geçici Kabul Detayları</h2>
                <p class="text-white-50 mb-0 fs-6">Dijital teslim dosyalarını, As-Built çizimleri, O&M kılavuzlarını ve garanti taleplerini yönetin.</p>
            </div>

            <div class="d-flex gap-2">
                @if($closeout->status !== 'delivered')
                    <a href="{{ route('closeout.edit', $closeout) }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm d-inline-flex align-items-center gap-1">
                        <i class="ri-edit-line fs-5"></i> Kontrol Listesini Düzenle
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 4 Spec Boxes -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="closeout-spec-box text-center">
                <div class="text-muted small fw-extrabold text-uppercase mb-1" style="letter-spacing: 0.5px;">KAPANIŞ DURUMU</div>
                @if($closeout->status === 'completed')
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold fs-6">Tamamlandı</span>
                @elseif($closeout->status === 'in_progress')
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold fs-6">Devam Ediyor</span>
                @elseif($closeout->status === 'delivered')
                    <span class="badge bg-purple-subtle text-purple border border-purple border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold fs-6" style="background: rgba(124, 58, 237, 0.1); color: #7c3aed;">İdareye Teslim Edildi</span>
                @else
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold fs-6">Taslak</span>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="closeout-spec-box text-center">
                <div class="text-muted small fw-extrabold text-uppercase mb-1" style="letter-spacing: 0.5px;">TAMAMLANMA ORANI</div>
                <h3 class="fw-extrabold text-success font-monospace mb-0">{{ $closeout->completion_percentage }}%</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="closeout-spec-box text-center">
                <div class="text-muted small fw-extrabold text-uppercase mb-1" style="letter-spacing: 0.5px;">SÜREÇ BAŞLANGICI</div>
                <h5 class="fw-bold text-dark mb-0"><i class="ri-calendar-line me-1 text-primary"></i>{{ $closeout->started_at ? $closeout->started_at->format('d.m.Y') : '-' }}</h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="closeout-spec-box text-center">
                <div class="text-muted small fw-extrabold text-uppercase mb-1" style="letter-spacing: 0.5px;">TESLİM TARİHİ</div>
                <h5 class="fw-bold text-dark mb-0"><i class="ri-calendar-check-line me-1 text-success"></i>{{ $closeout->completed_at ? $closeout->completed_at->format('d.m.Y') : '-' }}</h5>
            </div>
        </div>
    </div>

    <!-- Tabs Card Container -->
    <div class="closeout-glass-card">
        <ul class="nav nav-tabs mb-4 border-bottom" id="closeoutTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-extrabold" id="handover-tab" data-bs-toggle="tab" data-bs-target="#handover" type="button" role="tab">
                    <i class="ri-file-archive-fill me-1 text-indigo" style="color:#4338ca;"></i> Dijital Teslim Dosyası & Kontrol Listesi
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-extrabold" id="warranty-tab" data-bs-toggle="tab" data-bs-target="#warranty" type="button" role="tab">
                    <i class="ri-shield-check-fill me-1 text-warning"></i> Garanti Süreci & Talepler ({{ $closeout->warrantyClaims->count() }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="closeoutTabsContent">
            <!-- Handover Package Tab -->
            <div class="tab-pane fade show active" id="handover" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-extrabold text-dark mb-0"><i class="ri-checkbox-line text-success me-2"></i>Kapanış Kontrol Listesi Öğeleri</h5>
                    <span class="badge rounded-pill px-3 py-2 fw-extrabold" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">
                        {{ $closeout->items->where('status', 'completed')->count() }} / {{ $closeout->items->count() }} Tamamlandı
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table closeout-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">KATEGORİ</th>
                                <th>TESLİMAT MADDESİ</th>
                                <th>DURUM</th>
                                <th>EK DOKÜMANLAR</th>
                                <th>NOTLAR & DETAY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($closeout->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1.5 fw-bold">
                                            {{ $item->category }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-dark d-block fs-6">{{ $item->item }}</strong>
                                    </td>
                                    <td>
                                        @if($item->status === 'completed')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold d-inline-flex align-items-center gap-1">
                                                <i class="ri-checkbox-circle-fill"></i> Tamamlandı
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1.5 fw-extrabold d-inline-flex align-items-center gap-1">
                                                <i class="ri-time-line"></i> Bekliyor
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->documents && count($item->documents) > 0)
                                            @foreach($item->documents as $doc)
                                                <span class="badge bg-light text-dark border rounded-pill me-1 small"><i class="ri-attachment-line me-1"></i>{{ $doc }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-secondary fw-semibold small">{{ $item->notes ?: '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        Kontrol listesi öğesi bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($closeout->completion_percentage === 100)
                    <div class="alert alert-success border-0 rounded-4 mt-4 p-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(4, 120, 87, 0.25) 100%); border: 1.5px solid rgba(16, 185, 129, 0.4) !important;">
                        <div>
                            <h5 class="fw-extrabold text-success mb-1"><i class="ri-checkbox-circle-fill me-2 fs-4"></i>Tüm Teslimat Gereklilikleri Tamamlandı!</h5>
                            <p class="text-dark fw-bold mb-0 small">Dijital teslim paketiniz hazır. İşverene paylaşabilir veya arşive aktarabilirsiniz.</p>
                        </div>
                        <button type="button" class="btn btn-success text-white rounded-pill px-4 py-2.5 fw-extrabold shadow-sm" onclick="alert('Dijital teslimat paketi ZIP olarak indiriliyor...');" style="background:#16a34a; border:none;">
                            <i class="ri-download-cloud-line me-1 fs-5"></i> Dijital Paketi İndir & Paylaş
                        </button>
                    </div>
                @endif
            </div>

            <!-- Warranty Management Tab -->
            <div class="tab-pane fade" id="warranty" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-extrabold text-dark mb-0"><i class="ri-shield-flash-fill text-warning me-2 fs-4"></i>Garanti Süreci Talepleri</h5>
                    <a href="{{ route('closeout.create-warranty-claim', $closeout) }}" class="btn btn-warning text-dark rounded-pill px-4 py-2 fw-extrabold shadow-sm d-inline-flex align-items-center gap-1">
                        <i class="ri-add-circle-fill fs-5"></i> Yeni Garanti Talebi
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table closeout-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">TALEP NO</th>
                                <th>KONU & AÇIKLAMA</th>
                                <th>BİLDİRİM TARİHİ</th>
                                <th>İLGİLİ EKİPMAN</th>
                                <th>SORUMLU TAŞERON</th>
                                <th>DURUM</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($closeout->warrantyClaims as $claim)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1.5 fw-bold">
                                            {{ $claim->claim_no }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-dark d-block fs-6">{{ $claim->subject }}</strong>
                                        <small class="text-secondary fw-semibold text-truncate d-block" style="max-width: 250px;">{{ $claim->description }}</small>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold small"><i class="ri-calendar-line me-1 text-primary"></i>{{ $claim->notification_date ? $claim->notification_date->format('d.m.Y') : '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark"><i class="ri-tools-line me-1 text-info"></i>{{ $claim->equipment->name ?? 'Ekipman Belirtilmedi' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark"><i class="ri-building-line me-1 text-warning"></i>{{ $claim->contractor->name ?? 'Taşeron Belirtilmedi' }}</span>
                                    </td>
                                    <td>
                                        @if($claim->status === 'closed')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">Kapandı</span>
                                        @elseif($claim->status === 'intervened')
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">Müdahale Edildi</span>
                                        @elseif($claim->status === 'assigned')
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">Atandı</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1.5 fw-bold">Açık</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        Henüz kayıtlı garanti talebi bulunmuyor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
