@extends('layouts.master')

@section('title', 'Dinamik Raporlar & Doküman Arşivi (PDF/Excel)')

@push('styles')
<style>
    .dynrep-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #be123c 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(225, 29, 72, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .dynrep-badge {
        background: rgba(225, 29, 72, 0.2) !important;
        border: 1px solid rgba(225, 29, 72, 0.4) !important;
        color: #fecdd3 !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-dynrep {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-dynrep {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-dynrep {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-dynrep:focus {
        background-color: #ffffff !important;
        border-color: #e11d48 !important;
        box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 dynrep-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 dynrep-badge">
                            <i class="ri-file-chart-fill text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">OTOMATİK RAPOR ÜRETİCİ & YÖNETİCİ DOKÜMAN ARŞİVİ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Dinamik Raporlar & Doküman Arşivi</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Şantiye imalat ilerlemeleri, hakediş icmalleri, finansal nakit akışları ve İSG denetimlerinin otomatik PDF ve Excel formatlarında üretilmesi ve yönetici arşivi.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-folder-zip-line fs-5"></i>Toplam Rapor: {{ $stats['total_reports'] }} Dosya
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
            <div class="card custom-kpi-card-dynrep h-100 border-start border-danger border-4" style="border-left-color: #e11d48 !important;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">PDF YÖNETİM RAPORLARI</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.8rem;">{{ $stats['pdf_count'] }} PDF</div>
                        <small class="text-danger fw-bold"><i class="ri-file-pdf-line me-1"></i>Resmi PDF Çıktıları</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-file-pdf-fill fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-dynrep h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">EXCEL HESAP CETVELLERİ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $stats['excel_count'] }} XLSX</div>
                        <small class="text-success fw-bold"><i class="ri-file-excel-line me-1"></i>Dinamik Veri Cetveli</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-file-excel-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-dynrep h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM ARŞİVLENEEN</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $stats['total_reports'] }} Rapor</div>
                        <small class="text-primary fw-bold"><i class="ri-folder-3-line me-1"></i>Bulut Arşiv Odası</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-folder-chart-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-dynrep h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">RAPORLAMA DURUMU</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.5rem;">%100 Güncel</div>
                        <small class="text-warning fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>İndirmeye Hazır</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-download-cloud-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Report Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-dynrep shadow-sm p-4 h-100">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fff1f2; color: #e11d48;">
                        <i class="ri-file-add-line fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni Özel Rapor Üret</h5>
                </div>

                <form action="{{ route('dynamic_reports.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">RAPOR BAŞLIĞI <span class="text-danger">*</span></label>
                        <input type="text" name="report_title" class="form-control rounded-pill custom-input-dynrep" placeholder="Örn: 2026 Q3 Hakediş ve İmalat İlerleme Raporu" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">RAPOR KATEGORİSİ / TÜRÜ <span class="text-danger">*</span></label>
                        <select name="report_type" class="form-select rounded-pill custom-input-dynrep" required>
                            <option value="Hakediş & İmalat" selected>📊 Hakediş & İmalat İlerleme</option>
                            <option value="Finans & Nakit Akışı">💰 Finans & Nakit Akış Raporu</option>
                            <option value="İSG & Şantiye Güvenliği">🛡️ İSG & Şantiye Güvenlik Denetimi</option>
                            <option value="Lojistik & Depo Stok">🚚 Lojistik & Depo Malzeme Stok</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">RAPORU OLUŞTURAN <span class="text-danger">*</span></label>
                        <input type="text" name="generated_by" class="form-control rounded-pill custom-input-dynrep" placeholder="Örn: Şantiye Şefi / AI Rapor Robotu" value="{{ auth()->user()->name ?? 'Yönetim Sistemi' }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">DOSYA FORMATI <span class="text-danger">*</span></label>
                        <select name="file_format" class="form-select rounded-pill custom-input-dynrep" required>
                            <option value="PDF" selected>📄 PDF Dokümanı (.pdf)</option>
                            <option value="EXCEL">📊 EXCEL Tablosu (.xlsx)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 rounded-pill py-2.5 fw-extrabold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #e11d48; border-color: #e11d48;">
                        <i class="ri-file-chart-fill fs-5"></i>Raporu Oluştur & Arşivle
                    </button>
                </form>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-archive-drawer-fill text-warning fs-5"></i>Dinamik Yönetici Raporları & Arşiv Cetveli
                    </h5>
                    <span class="badge bg-danger text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #e11d48;">{{ $items->total() }} Rapor</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4 py-3 fw-extrabold text-dark">Rapor Başlığı</th>
                                    <th class="py-3 fw-extrabold text-dark">Kategori / Tür</th>
                                    <th class="py-3 fw-extrabold text-dark">Oluşturan</th>
                                    <th class="py-3 fw-extrabold text-dark">Format</th>
                                    <th class="py-3 fw-extrabold text-dark">Oluşturulma Tarihi</th>
                                    <th class="pe-4 text-end py-3 fw-extrabold text-dark">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-extrabold text-dark fs-6">{{ $item->report_title }}</div>
                                            <small class="text-secondary fw-semibold">Sistem Çıktısı</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark font-monospace text-warning px-2.5 py-1 rounded-pill fw-bold">
                                                {{ $item->report_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->generated_by }}</div>
                                            <small class="text-muted">Yetkili Kullanıcı</small>
                                        </td>
                                        <td>
                                            @if($item->file_format === 'PDF')
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                                                    <i class="ri-file-pdf-fill me-1"></i>PDF Dokümanı
                                                </span>
                                            @else
                                                <span class="badge text-white rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a; font-size: 11px;">
                                                    <i class="ri-file-excel-fill me-1"></i>EXCEL Tablosu
                                                </span>
                                            @endif
                                        </td>
                                        <td class="font-monospace fw-bold text-dark" style="font-size: 11.5px;">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d.m.Y H:i') }}
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-inline-flex gap-1">
                                                <button class="btn btn-sm btn-outline-primary rounded-pill px-2.5" title="İndir">
                                                    <i class="ri-download-2-line me-1"></i>İndir
                                                </button>
                                                <form action="{{ route('dynamic_reports.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu dinamik raporu silmek istediğinize emin misiniz?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1.5" title="Sil">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="ri-file-chart-line display-4 text-secondary mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Dinamik Rapor Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">Sol taraftaki formdan rapor başlığı ve formatını seçerek ilk özel yönetim raporunuzu üretebilirsiniz.</p>
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
