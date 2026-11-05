@extends('layouts.master')

@section('title', 'IoT Filo & İş Makinesi Telemetri Kontrol Merkezi')

@push('styles')
<style>
    .fleet-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0284c7 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(2, 132, 199, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .fleet-badge {
        background: rgba(2, 132, 199, 0.2) !important;
        border: 1px solid rgba(2, 132, 199, 0.4) !important;
        color: #bae6fd !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-fleet {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-fleet {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-fleet {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-fleet:focus {
        background-color: #ffffff !important;
        border-color: #0284c7 !important;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 fleet-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 fleet-badge">
                            <i class="ri-radar-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">CAN-BUS IOT SENSÖR VERİSİ & CANLI FİLO TAKİBİ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">IoT Filo & İş Makinesi Telemetri</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Şantiye kule vinçleri, ekskavatörler, transmikserler ve kamyonların canlı CAN-BUS GPS telemetri verileri, rölanti süresi takibi ve yakıt verimliliği analitiği.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-speed-up-line fs-5"></i>Filo Verimliliği: %{{ number_format($stats['efficiency_rate'], 1) }}
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
            <div class="card custom-kpi-card-fleet h-100 border-start border-info border-4" style="border-left-color: #0284c7 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TAKİP EDİLEN FİLO</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['vehicle_count'] }} Makine</div>
                        <small class="text-info fw-bold"><i class="ri-truck-line me-1"></i>Aktif IoT Cihazı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-truck-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-fleet h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM ÇALIŞMA SAATİ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">{{ number_format($stats['total_operating_hours'], 1, ',', '.') }} Sa</div>
                        <small class="text-success fw-bold"><i class="ri-time-line me-1"></i>Aktif İmalat Çalışması</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-timer-flash-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-fleet h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">HARCANAN YAKIT</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">{{ number_format($stats['total_fuel_liters'], 1, ',', '.') }} Lt</div>
                        <small class="text-warning fw-bold"><i class="ri-drop-line me-1"></i>Motorin / Mazot Tüketimi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-gas-station-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-fleet h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM RÖLANTİ SÜRESİ</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.6rem;">{{ number_format($stats['total_idle_hours'], 1, ',', '.') }} Sa</div>
                        <small class="text-danger fw-bold"><i class="ri-alert-line me-1"></i>Gereksiz Motor Çalışması</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-time-fill fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Telemetry Record Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-fleet shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #f0f9ff; color: #0284c7;">
                        <i class="ri-radar-fill fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni Telemetri Verisi Ekle</h5>
                </div>

                <form action="{{ route('fleet_telemetries.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ARAÇ PLAKASI / MAKİNE KODU <span class="text-danger">*</span></label>
                        <input type="text" name="vehicle_plate" class="form-control rounded-pill custom-input-fleet font-monospace" placeholder="Örn: 34 CKN 842 veya VINÇ-01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">MAKİNE TİPİ <span class="text-danger">*</span></label>
                        <select name="vehicle_type" class="form-select rounded-pill custom-input-fleet" required>
                            <option value="Ekskavatör" selected>🚜 Ekskavatör (Paletli/Tekerlekli)</option>
                            <option value="Kule Vinç">🏗️ Kule Vinç / Mobil Vinç</option>
                            <option value="Beton Kamyonu / Transmikser">🚛 Beton Transmikseri</option>
                            <option value="Dozer / Kepçe">🚜 Dozer / Loder Kepçe</option>
                            <option value="Damperli Hafriyat Kamyonu">🚚 Damperli Kamyon</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">KAYIT TARİHİ <span class="text-danger">*</span></label>
                        <input type="date" name="record_date" class="form-control rounded-pill custom-input-fleet" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">ÇALIŞMA (SAAT) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="operating_hours" class="form-control rounded-pill custom-input-fleet text-success font-monospace fw-bold" placeholder="8.5" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">RÖLANTİ (SAAT) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="idle_hours" class="form-control rounded-pill custom-input-fleet text-danger font-monospace fw-bold" placeholder="1.0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">HARCANAN YAKIT (LİTRE) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" name="fuel_consumed_liters" class="form-control rounded-pill custom-input-fleet text-primary font-monospace fw-bold" placeholder="Örn: 140.0" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">OPERATÖR ADI SOYADI</label>
                        <input type="text" name="operator_name" class="form-control rounded-pill custom-input-fleet" placeholder="Örn: Hasan Usta">
                    </div>

                    <button type="submit" class="btn btn-info w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #0284c7; border-color: #0284c7;">
                        <i class="ri-save-3-fill fs-5"></i>Telemetri Verisini Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-list-check-2 text-warning fs-5"></i>IoT Filo Telemetri Kayıt Cetveli
                    </h5>
                    <span class="badge bg-info text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #0284c7;">{{ $items->total() }} Kayıt</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Tarih</th>
                                    <th class="py-3 fw-extrabold text-dark">Plaka / Kod</th>
                                    <th class="py-3 fw-extrabold text-dark">Makine Tipi & Operatör</th>
                                    <th class="py-3 fw-extrabold text-dark">Çalışma (Sa)</th>
                                    <th class="py-3 fw-extrabold text-dark">Rölanti (Sa)</th>
                                    <th class="py-3 fw-extrabold text-dark">Yakıt (Lt)</th>
                                    <th class="py-3 fw-extrabold text-dark">Verimlilik</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    @php
                                        $tot = floatval($item->operating_hours) + floatval($item->idle_hours);
                                        $eff = $tot > 0 ? (floatval($item->operating_hours) / $tot) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 py-3 font-monospace fw-bold text-dark">
                                            {{ \Carbon\Carbon::parse($item->record_date)->format('d.m.Y') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-dark text-warning font-monospace px-3 py-1.5 rounded-pill fw-extrabold" style="font-size: 11.5px;">
                                                {{ $item->vehicle_plate }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-extrabold text-dark fs-6">{{ $item->vehicle_type }}</div>
                                            <small class="text-secondary fw-semibold">Operatör: {{ $item->operator_name ?? 'Atanmadı' }}</small>
                                        </td>
                                        <td class="font-monospace fw-extrabold text-success" style="font-size: 1.05rem;">
                                            {{ number_format($item->operating_hours, 1) }} sa
                                        </td>
                                        <td class="font-monospace fw-extrabold text-danger" style="font-size: 1.05rem;">
                                            {{ number_format($item->idle_hours, 1) }} sa
                                        </td>
                                        <td class="font-monospace fw-extrabold text-primary" style="font-size: 1.05rem;">
                                            {{ number_format($item->fuel_consumed_liters, 1) }} Lt
                                        </td>
                                        <td>
                                            <span class="badge {{ $eff >= 80 ? 'bg-success-subtle text-success border border-success' : 'bg-warning-subtle text-warning-emphasis border border-warning' }} rounded-pill px-2.5 py-1 font-monospace fw-bold">
                                                %{{ number_format($eff, 1) }}
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('fleet_telemetries.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu telemetri kaydını silmek istediğinize emin misiniz?');">
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
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <i class="ri-radar-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz IoT Telemetri Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan iş makinesi plakası, çalışma saati ve rölanti sürelerini girerek telemetri verisi ekleyebilirsiniz.</p>
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
