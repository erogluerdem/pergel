@extends('layouts.master')

@section('title', 'İnşaat İK & Özlük Dosyaları (Beyaz Yaka)')

@push('styles')
<style>
    .hr-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e3c72 60%, #2563eb 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(37, 99, 235, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .hr-badge {
        background: rgba(37, 99, 235, 0.2) !important;
        border: 1px solid rgba(37, 99, 235, 0.4) !important;
        color: #93c5fd !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-hr {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-hr {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-hr {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-hr:focus {
        background-color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 hr-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 hr-badge">
                            <i class="ri-user-star-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">4857 SAYILI İŞ KANUNU & BEYAZ YAKA İK ÖZLÜK YÖNETİMİ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">İnşaat İK & Özlük Dosyaları</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Şantiye proje müdürleri, şantiye şefleri, mimar, mühendis ve teknik personelin özlük dosyaları, yıllık izin hakları ve maaş bordro takibi.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-wallet-line fs-5"></i>Aylık Toplam Bordro: ₺{{ number_format($stats['total_monthly_payroll'], 0, ',', '.') }}
                        </span>
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

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-hr h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KADROLU PERSONEL</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_personnel'] }} Personel</div>
                        <small class="text-primary fw-bold"><i class="ri-team-line me-1"></i>Teknik & İdari Kadro</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-group-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-hr h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">AKTİF GÖREVDE</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $stats['active_count'] }} Personel</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Şantiye / Ofis Başında</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-user-follow-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-hr h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">AYLIK BORDRO HACMİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($stats['total_monthly_payroll'], 2, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-hand-coin-line me-1"></i>Net Maaş Toplamı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-wallet-3-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-hr h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">İZİNDEKİ PERSONEL</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ $stats['on_leave_count'] }} Personel</div>
                        <small class="text-warning fw-bold"><i class="ri-plane-line me-1"></i>Yıllık İznini Kullanan</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-user-shared-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Personnel Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-hr shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #eff6ff; color: #2563eb;">
                        <i class="ri-user-add-fill fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni Personel Ekle</h5>
                </div>

                <form action="{{ route('hr_personnels.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">PERSONEL ADI SOYADI <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control rounded-pill custom-input-hr" placeholder="Örn: İnş. Müh. Ahmet Yılmaz" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">UNVAN / GÖREV <span class="text-danger">*</span></label>
                        <select name="position" class="form-select rounded-pill custom-input-hr" required>
                            <option value="Proje Müdürü" selected>🏗️ Proje Müdürü</option>
                            <option value="Şantiye Şefi">👷 Şantiye Şefi</option>
                            <option value="İnşaat Mühendisi">📐 İnşaat Mühendisi</option>
                            <option value="Mimar / İç Mimar">🏛️ Mimar / İç Mimar</option>
                            <option value="Harita Teknikeri">🗺️ Harita Teknikeri</option>
                            <option value="İSG Uzmanı">🛡️ İSG Uzmanı (A/B/C)</option>
                            <option value="Muhasebe / İK">💼 Muhasebe / İK Uzmanı</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">İŞE GİRİŞ TARİHİ <span class="text-danger">*</span></label>
                        <input type="date" name="hire_date" class="form-control rounded-pill custom-input-hr" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">KALAN İZİN (GÜN) <span class="text-danger">*</span></label>
                            <input type="number" name="remaining_leaves" class="form-control rounded-pill custom-input-hr font-monospace text-primary fw-bold" value="14" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">NET MAAŞ (TL) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="salary" class="form-control rounded-pill custom-input-hr text-success font-monospace fw-bold" placeholder="75000.00" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">ÇALIŞMA DURUMU <span class="text-danger">*</span></label>
                        <select name="status" class="form-select rounded-pill custom-input-hr" required>
                            <option value="aktif" selected>🟢 Aktif Görevde</option>
                            <option value="izinde">🔵 İzinde (Yıllık İzin)</option>
                            <option value="ayrildi">🔴 Ayrıldı (İlişik Kesildi)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #2563eb; border-color: #2563eb;">
                        <i class="ri-save-3-fill fs-5"></i>Personel Kaydını Oluştur
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-team-fill text-warning fs-5"></i>Beyaz Yaka İK & Özlük Personel Listesi
                    </h5>
                    <span class="badge bg-primary text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #2563eb;">{{ $items->total() }} Personel</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Personel Ad Soyad</th>
                                    <th class="py-3 fw-extrabold text-dark">Görev / Unvan</th>
                                    <th class="py-3 fw-extrabold text-dark">İşe Giriş Tarihi</th>
                                    <th class="py-3 fw-extrabold text-dark">Kalan İzin</th>
                                    <th class="py-3 fw-extrabold text-dark">Net Maaş (TL)</th>
                                    <th class="py-3 fw-extrabold text-dark">Durum</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold font-monospace" style="width: 36px; height: 36px;">
                                                    {{ strtoupper(substr($item->full_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-extrabold text-dark fs-6">{{ $item->full_name }}</div>
                                                    <small class="text-secondary fw-semibold">Kadrolu Personel</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark font-monospace text-warning px-2.5 py-1 rounded-pill fw-bold">
                                                {{ $item->position }}
                                            </span>
                                        </td>
                                        <td class="font-monospace fw-bold text-dark" style="font-size: 11.5px;">
                                            {{ \Carbon\Carbon::parse($item->hire_date)->format('d.m.Y') }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $item->remaining_leaves < 5 ? 'bg-danger text-white' : 'bg-success-subtle text-success border border-success' }} rounded-pill px-2.5 py-1 font-monospace fw-bold">
                                                {{ $item->remaining_leaves }} Gün İzin
                                            </span>
                                        </td>
                                        <td class="font-monospace fw-extrabold text-success" style="font-size: 1.05rem;">
                                            ₺{{ number_format($item->salary, 2, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($item->status === 'aktif')
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>Aktif Görevde
                                                </span>
                                            @elseif($item->status === 'izinde')
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #0284c7; font-size: 11px;">
                                                    <i class="ri-plane-line me-1"></i>Yıllık İzinde
                                                </span>
                                            @else
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-user-unfollow-line me-1"></i>Ayrıldı
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('hr_personnels.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu personel kaydını silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Sil">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="ri-user-star-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Personel Özlük Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan personel adı, unvanı, işe giriş tarihi ve net maaşını girerek ilk özlük kaydınızı oluşturabilirsiniz.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
