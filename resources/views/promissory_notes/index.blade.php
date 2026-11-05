@extends('layouts.master')

@section('title', 'Müşteri Senetleri & Portföy Yönetimi')

@push('styles')
<style>
    .note-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .note-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: noteGlow 4s linear infinite;
    }

    @keyframes noteGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .note-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-note {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-note:hover {
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

    .custom-input-note {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 9px 13px !important;
    }

    .custom-input-note:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .form-label-custom {
        font-weight: 800 !important;
        color: #0f172a !important;
        font-size: 0.85rem !important;
        margin-bottom: 5px !important;
    }

    .custom-note-table thead,
    .custom-note-table thead tr,
    .custom-note-table thead th {
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

    .custom-note-table td {
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
    <div class="note-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill note-badge-pill">
                        <i class="ri-file-paper-2-fill text-warning fs-5"></i> FİRMA İÇİ SENETLİ SATIŞ & TAHSİLAT PORTFÖYÜ
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $notes->total() }} Kayıtlı Senet
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Müşteri Senetleri & Vade Portföyü</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Konut ve gayrimenkul satışlarından alınan senetlerin vade takibi, kasadaki portföy tutarı, tedarikçilere yapılan ciro ve icra/protesto takipleri.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-warning text-dark px-4 py-3 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm fs-6 font-monospace">
                    <i class="ri-coins-fill fs-4"></i>Portföy Hacmi: ₺{{ number_format($stats['total_portfolio'], 0, ',', '.') }}
                </span>
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

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-note h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">KASADAKİ SENETLER</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_portfolio'], 2, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-safe-2-fill me-1"></i>Portföyde Bekleyen</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-safe-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-note h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TAHSİL EDİLEN SENETLER</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_collected'], 2, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Nakit Girişi Yapıldı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-bank-card-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-note h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">CİRO EDİLEN (TEDARİKÇİ)</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_endorsed'], 2, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-arrow-go-forward-fill me-1"></i>Tedarikçiye Devredildi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-refund-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-note h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">PROTESTOLU / İCRADA</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_protested'], 2, ',', '.') }}</div>
                        <small class="text-danger fw-bold"><i class="ri-gavel-fill me-1"></i>Hukuki Süreçte</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fef2f2 !important; border: 1px solid #fecaca !important;">
                        <i class="ri-alarm-warning-fill fs-2" style="color: #dc2626 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="row g-4 mb-5">
        <!-- ADD NOTE FORM COLUMN -->
        <div class="col-lg-4">
            <div class="card custom-card-container h-100">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-add-circle-fill text-warning fs-5"></i>Yeni Senet Kaydı Ekle
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('promissory_notes.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label-custom">MÜŞTERİ SEÇİNİZ</label>
                            <select name="customer_id" class="form-select custom-input-note">
                                <option value="">Müşteri Seçiniz...</option>
                                @foreach($customers as $id => $name)
                                    <option value="{{ $id }}">👤 {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">PROJE SEÇİNİZ</label>
                            <select name="project_id" class="form-select custom-input-note">
                                <option value="">Proje Seçiniz...</option>
                                @foreach($projects as $id => $name)
                                    <option value="{{ $id }}">🏗️ {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">SENET / KOÇAN NO <span class="text-danger">*</span></label>
                            <input type="text" name="note_number" class="form-control custom-input-note font-monospace" placeholder="Örn: SNT-2026-001" required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label-custom">TUTAR (TL) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount" class="form-control custom-input-note font-monospace text-success fw-bold" placeholder="Örn: 150000" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label-custom">VADE TARİHİ <span class="text-danger">*</span></label>
                                <input type="date" name="due_date" class="form-control custom-input-note" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">BORÇLU ADI / T.C. VKN <span class="text-danger">*</span></label>
                            <input type="text" name="debtor_name" class="form-control custom-input-note" placeholder="Örn: Mehmet Demir (12345678901)" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">KEFİL ADI SOYADI</label>
                            <input type="text" name="guarantor_name" class="form-control custom-input-note" placeholder="Varsa Kefil Adı">
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">SENET PORTFÖY DURUMU <span class="text-danger">*</span></label>
                            <select name="status" class="form-select custom-input-note" required>
                                <option value="portfolio" selected>Kasada (Portföyde)</option>
                                <option value="collected">Tahsil Edildi</option>
                                <option value="endorsed">Ciro Edildi (Tedarikçiye Devredildi)</option>
                                <option value="protested">Protesto Çekildi</option>
                                <option value="legal_action">İcrada / Hukukta</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-warning text-dark rounded-pill py-2.5 w-100 fw-extrabold shadow-sm d-flex align-items-center justify-content-center gap-2">
                            <i class="ri-save-fill fs-5"></i>Senedi Kaydet
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TABLE COLUMN -->
        <div class="col-lg-8">
            <div class="card custom-card-container h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-file-list-3-fill text-warning fs-5"></i>Senet Portföy Listesi & Tahsilat Vadesi
                    </h5>
                    <div class="d-flex gap-1">
                        <a href="{{ route('promissory_notes.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-extrabold">Tümü</a>
                        <a href="{{ route('promissory_notes.index', ['status' => 'portfolio']) }}" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-extrabold">Kasada</a>
                        <a href="{{ route('promissory_notes.index', ['status' => 'collected']) }}" class="btn btn-sm btn-success text-white rounded-pill px-3 fw-extrabold" style="background: #16a34a !important;">Tahsil</a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table custom-note-table align-middle mb-0">
                            <thead style="background-color: #0f172a !important;">
                                <tr style="background-color: #0f172a !important;">
                                    <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Senet No</th>
                                    <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Borçlu / Müşteri</th>
                                    <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Vade Tarihi</th>
                                    <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Tutar</th>
                                    <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Portföy Durumu</th>
                                    <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notes as $note)
                                    <tr>
                                        <td class="ps-4 py-3 font-monospace">
                                            <span class="badge bg-dark text-warning border border-warning rounded-pill px-3 py-1.5 font-monospace fw-extrabold">
                                                {{ $note->note_number }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="fw-extrabold text-dark d-block fs-6">{{ $note->debtor_name }}</strong>
                                            <small class="text-muted fw-bold">👤 {{ $note->customer->name ?? $note->project->name ?? 'Firma Müşterisi' }}</small>
                                        </td>
                                        <td class="font-monospace fw-extrabold text-primary">
                                            📅 {{ \Carbon\Carbon::parse($note->due_date)->format('d.m.Y') }}
                                        </td>
                                        <td class="text-end font-monospace">
                                            <strong class="text-success font-monospace fs-6 fw-extrabold">₺{{ number_format($note->amount, 2, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-center font-monospace">
                                            @if($note->status === 'portfolio')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">✓ KASADA (PORTFÖYDE)</span>
                                            @elseif($note->status === 'collected')
                                                <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">🎉 TAHSİL EDİLDİ</span>
                                            @elseif($note->status === 'endorsed')
                                                <span class="badge bg-info text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">🔄 CİRO EDİLDİ</span>
                                            @elseif($note->status === 'protested')
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #ef4444 !important;">🚨 PROTESTOLU</span>
                                            @else
                                                <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">⚖️ HUKUKİ İCRADA</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('promissory_notes.destroy', $note->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'Senet Kaydını Sil?', 'Bu senet kaydı silinecektir.')" title="Sil">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="ri-file-paper-2-line display-4 text-warning mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Senet Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan borçlu müşteri bilgilerini ve vade tarihini girerek ilk senet kaydınızı oluşturabilirsiniz.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 d-flex justify-content-center">
                        {{ $notes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
