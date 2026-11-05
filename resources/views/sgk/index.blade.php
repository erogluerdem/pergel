@extends('layouts.master')

@section('title', 'SGK Asgari İşçilik Hesaplayıcı & İskan Takibi')

@push('styles')
<style>
    .sgk-hero-banner {
        background: linear-gradient(135deg, #064e3b 0%, #022c22 60%, #0d9488 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        box-shadow: 0 15px 35px rgba(6, 78, 59, 0.35) !important;
    }
    
    .sgk-badge {
        background: rgba(16, 185, 129, 0.2) !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        color: #a7f3d0 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-sgk {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-sgk {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-sgk {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-sgk:focus {
        background-color: #ffffff !important;
        border-color: #059669 !important;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 sgk-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 sgk-badge">
                            <i class="ri-shield-check-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">TC SGK İŞÇİLİK MEVZUATI & İSKAN</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">SGK Asgari İşçilik & İskan İlişiksizlik Takibi</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            ÇŞB Yapı Birim Maliyetleri cetveline göre projenizin zorunlu SGK işçilik tutarını hesaplayın, bildirilen matrahları ve iskan öncesi prim ilişiksizlik durumunu canlı takip edin.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2">
                            <i class="ri-government-line fs-5"></i>ÇŞB 2026 Maliyet Cetveli
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

    @php
        $totalRequired = $calculations->sum('required_labor_amount');
        $totalReported = $calculations->sum('reported_labor_amount');
        $totalRemaining = $calculations->sum('remaining_labor_amount');
        $highRiskCount = $calculations->where('risk_status', 'high')->count();
    @endphp

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-sgk h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">HESAPLANAN SGK PROJESİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $calculations->total() }} Kayıt</div>
                        <small class="text-primary fw-bold"><i class="ri-building-line me-1"></i>Kayıtlı Projeler</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-calculator-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-sgk h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ZORUNLU SGK İŞÇİLİK TUTARI</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.55rem;">₺{{ number_format($totalRequired, 0, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-file-shield-line me-1"></i>Asgari Matrah Matrisi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-shield-user-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-sgk h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">BİLDİRİLEN SGK BİLDİRGESİ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.55rem;">₺{{ number_format($totalReported, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Ödenen & Bildirilen</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-money-dollar-box-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-sgk h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">NET EKSİK İŞÇİLİK RİSKİ</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.55rem;">₺{{ number_format($totalRemaining, 0, ',', '.') }}</div>
                        <small class="text-danger fw-bold"><i class="ri-error-warning-line me-1"></i>{{ $highRiskCount }} Projede İskan Engeli Risk</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fef2f2 !important; border: 1px solid #fecaca !important;">
                        <i class="ri-alarm-warning-line fs-2" style="color: #dc2626 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-sgk shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-emerald-100 text-emerald p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #ecfdf5;">
                        <i class="ri-calculator-line text-emerald fs-4" style="color: #059669;"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">SGK İşçilik Hesapla</h5>
                </div>

                <form action="{{ route('sgk.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">PROJE SEÇİNİZE</label>
                        <select name="project_id" class="form-select rounded-pill custom-input-sgk" required>
                            <option value="">Proje Seçin...</option>
                            @foreach($projects as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">YAPI İNŞAAT ALANI (m²)</label>
                        <input type="number" step="0.01" name="building_area_sqm" id="building_area_sqm" class="form-control rounded-pill custom-input-sgk" placeholder="Örn: 5000" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ÇŞB YAPI SINIFI VE GRUBU</label>
                        <select name="structure_class" id="structure_class" class="form-select rounded-pill custom-input-sgk" required onchange="updateUnitCost()">
                            @foreach($structureClasses as $code => $info)
                                <option value="{{ $code }}" data-cost="{{ $info['cost'] }}">{{ $info['name'] }} ({{ number_format($info['cost'], 0, ',', '.') }} TL/m²)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ÇŞB BİRİM MALİYET (TL/m²)</label>
                        <input type="number" step="0.01" name="unit_cost_per_sqm" id="unit_cost_per_sqm" class="form-control rounded-pill custom-input-sgk" value="22400" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ASGARİ İŞÇİLİK ORANI (%)</label>
                        <input type="number" step="0.01" name="mandatory_labor_rate" class="form-control rounded-pill custom-input-sgk" value="6.75" required>
                        <small class="text-muted extra-small d-block mt-1">Müteahhitlik işlerinde kanuni asgari işçilik oranı ortalama %6.75'tir.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">BİLDİRİLEN SGK PRİM TUTARI (TL)</label>
                        <input type="number" step="0.01" name="reported_labor_amount" class="form-control rounded-pill custom-input-sgk" value="0">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">AÇIKLAMA & NOTLAR</label>
                        <textarea name="notes" class="form-control rounded-4 custom-input-sgk" rows="2" placeholder="Örn: Ruhsat tarihi, müteahhit bildirim notları..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2.5 fw-extrabold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-add-circle-fill fs-5"></i>Hesapla & Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-list-check-2 text-warning fs-5"></i>SGK İşçilik & İskan İlişiksizlik Durum Listesi
                    </h5>
                    <span class="badge bg-primary rounded-pill px-3 py-1 fw-bold">{{ $calculations->total() }} Kayıt</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Proje Adı</th>
                                    <th class="py-3 fw-extrabold text-dark">İnşaat m²</th>
                                    <th class="py-3 fw-extrabold text-dark">Yapı Sınıfı</th>
                                    <th class="py-3 fw-extrabold text-dark">Zorunlu SGK Tutarı</th>
                                    <th class="py-3 fw-extrabold text-dark">Bildirilen SGK</th>
                                    <th class="py-3 fw-extrabold text-dark">Eksik İşçilik</th>
                                    <th class="py-3 fw-extrabold text-dark">İskan Riski</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($calculations as $calc)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-success bg-opacity-15 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                                                    <i class="ri-building-2-line fs-5"></i>
                                                </div>
                                                <span class="fw-extrabold text-dark">{{ $calc->project->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="fw-bold font-monospace text-dark">{{ number_format($calc->building_area_sqm, 0, ',', '.') }} m²</td>
                                        <td><span class="badge bg-secondary-subtle text-dark border rounded-pill px-2.5 py-1 font-monospace fw-bold">{{ $calc->structure_class }}</span></td>
                                        <td class="text-primary fw-extrabold font-monospace">₺{{ number_format($calc->required_labor_amount, 2, ',', '.') }}</td>
                                        <td class="text-success fw-extrabold font-monospace">₺{{ number_format($calc->reported_labor_amount, 2, ',', '.') }}</td>
                                        <td class="text-danger fw-extrabold font-monospace">₺{{ number_format($calc->remaining_labor_amount, 2, ',', '.') }}</td>
                                        <td>
                                            @if($calc->risk_status === 'low')
                                                <span class="badge bg-success text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>İskana Hazır (%90+)
                                                </span>
                                            @elseif($calc->risk_status === 'medium')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-alert-fill me-1"></i>Orta Risk (%50-90)
                                                </span>
                                            @else
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-close-circle-fill me-1"></i>Yüksek Risk (Eksik)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('sgk.destroy', $calc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu SGK hesabını silmek istediğinize emin misiniz?');">
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
                                            <i class="ri-shield-cross-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Kayıtlı SGK İşçilik Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan projenizin m² ve yapı sınıfını seçerek ilk SGK asgari işçilik hesabınızı oluşturabilirsiniz.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $calculations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function updateUnitCost() {
    var select = document.getElementById('structure_class');
    var selectedOption = select.options[select.selectedIndex];
    var cost = selectedOption.getAttribute('data-cost');
    if (cost) {
        document.getElementById('unit_cost_per_sqm').value = cost;
    }
}
</script>
@endsection
