@extends('layouts.master')

@section('title', 'Taşeron İSG & Saha Giriş Kapısı Evrak Kontrolü')

@push('styles')
<style>
    .worker-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
    }
    
    .worker-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-worker {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-form-card-worker {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
    }

    .custom-input-worker {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
    }
    .custom-input-worker:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .worker-entry-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        transition: all 0.25s ease !important;
    }
    .worker-entry-card:hover {
        border-color: #d97706 !important;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Hero Header Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 worker-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 worker-badge">
                            <i class="ri-user-shield-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">İSG 6331 KANUNU & CANLI SAHA GİRİŞ KAPISI</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Taşeron İSG & Saha Giriş Kapısı Evrak Kontrolü</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 750px; line-height: 1.6;">
                            Şantiyelerinize giriş yapacak tüm taşeron ustaları ve saha personelinin SGK işe giriş bildirgeleri, Ek-2 Sağlık Raporları, İSG Eğitim Sertifikaları ve Turnike Giriş İzinleri.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning text-dark px-3.5 py-2.5 rounded-pill fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="ri-shield-check-line fs-5"></i>6331 Sayılı İSG Mevzuatı
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
        $totalWorkers = $workers->total();
        $approvedCount = $workers->where('entry_permit_status', 'approved')->count();
        $blockedCount = $workers->where('entry_permit_status', 'blocked')->count();
    @endphp

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-worker h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KAYITLI TAŞERON PERSONELİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.8rem;">{{ $totalWorkers }} Personel</div>
                        <small class="text-warning fw-bold"><i class="ri-team-line me-1"></i>Saha Usta Portföyü</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-user-star-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-worker h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">SAHA GİRİŞ İZİNLİ (YEŞİL)</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $approvedCount }} Kişi</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Evrakları Tamam</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-shield-check-line fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-worker h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">GİRİŞ ENGELLENDİ (KIRMIZI)</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.8rem;">{{ $blockedCount }} Kişi</div>
                        <small class="text-danger fw-bold"><i class="ri-forbid-line me-1"></i>Eksik SGK / İSG Evrakı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-shield-user-line fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-worker h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">YÜKLENEN İSG EVRAKI</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.8rem;">{{ $workers->pluck('documents')->flatten()->count() }} Belge</div>
                        <small class="text-info fw-bold"><i class="ri-file-text-line me-1"></i>Ek-2 / İSG Sertifikası</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-file-shield-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- New Worker Form Column -->
        <div class="col-lg-4">
            <div class="card custom-form-card-worker shadow-sm p-4 mb-4">
                <div class="border-bottom pb-3 mb-3 d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fffbeb; color: #d97706;">
                        <i class="ri-user-add-line fs-4"></i>
                    </div>
                    <h5 class="fw-extrabold text-dark mb-0">Yeni Taşeron Çalışanı Kaydet</h5>
                </div>

                <form action="{{ route('subcontractor_workers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">TAŞERON FİRMA <span class="text-danger">*</span></label>
                        <select name="subcontractor_id" class="form-select rounded-pill custom-input-worker" required>
                            <option value="">Firma Seçiniz...</option>
                            @foreach($subcontractors as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">ÇALIŞILACAK PROJE / ŞANTİYE</label>
                        <select name="project_id" class="form-select rounded-pill custom-input-worker">
                            <option value="">Proje Seçiniz...</option>
                            @foreach($projects as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">ADI <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control rounded-pill custom-input-worker" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-extrabold text-dark small mb-1">SOYADI <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control rounded-pill custom-input-worker" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">T.C. KİMLİK NO</label>
                        <input type="text" name="tc_no" class="form-control rounded-pill custom-input-worker font-monospace" maxlength="11" placeholder="11 Haneli T.C. Kimlik No">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-extrabold text-dark small mb-1">MESLEK / USTALIK UNVANI</label>
                        <input type="text" name="job_title" class="form-control rounded-pill custom-input-worker" placeholder="Örn: Demirciler Ustası, İskele Kurulum Personeli">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-extrabold text-dark small mb-1">SGK İŞE GİRİŞ BİLDİRGE DURUMU <span class="text-danger">*</span></label>
                        <select name="sgk_status" class="form-select rounded-pill custom-input-worker" required>
                            <option value="active" selected>🟢 Aktif SGK İşe Giriş Bildirgesi Var</option>
                            <option value="pending">🟡 Onay Bekliyor</option>
                            <option value="missing">🔴 SGK İşe Giriş Yok (Giriş Engellenecek)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 rounded-pill py-2.5 fw-extrabold text-dark shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="ri-save-3-fill fs-5"></i>Personeli Kaydet
                    </button>
                </form>
            </div>
        </div>

        <!-- Worker Entry Cards Column -->
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-id-card-fill text-warning fs-5"></i>Saha Giriş İzin Listesi & Evrak Durumları
                    </h5>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-bold">{{ $workers->total() }} Personel</span>
                </div>

                <div class="card-body p-4">
                    @forelse($workers as $worker)
                        <div class="worker-entry-card p-4 mb-4 shadow-sm">
                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3 border-bottom pb-3">
                                <div>
                                    <h5 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                                        <i class="ri-user-3-fill text-primary"></i>{{ $worker->full_name }}
                                        <span class="badge bg-light text-dark border font-monospace fw-bold" style="font-size: 12px;">{{ $worker->job_title ?? 'Saha Personeli' }}</span>
                                    </h5>
                                    <small class="text-secondary fw-semibold">
                                        <i class="ri-building-line text-warning me-1"></i>Firma: <strong class="text-dark">{{ $worker->subcontractor->company_name ?? '-' }}</strong> | T.C: <strong class="font-monospace text-dark">{{ $worker->tc_no ?? 'Girilmedi' }}</strong>
                                    </small>
                                </div>
                                <div>
                                    @if($worker->entry_permit_status === 'approved')
                                        <span class="badge text-white rounded-pill px-3.5 py-2 fw-extrabold shadow-sm" style="background: #16a34a; font-size: 12px;">
                                            <i class="ri-checkbox-circle-fill me-1"></i>SAHA GİRİŞ İZİNLİ (YEŞİL)
                                        </span>
                                    @else
                                        <span class="badge text-white rounded-pill px-3.5 py-2 fw-extrabold shadow-sm" style="background: #dc2626; font-size: 12px;">
                                            <i class="ri-forbid-line me-1"></i>GİRİŞ ENGELLENDİ (KIRMIZI)
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Attached Documents Table -->
                            <h6 class="fw-extrabold text-dark mb-2.5 d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                                <i class="ri-file-shield-2-fill text-info"></i>İSG & İşe Giriş Evrakları ({{ $worker->documents->count() }} Belge):
                            </h6>
                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-bordered align-middle mb-0 bg-white">
                                    <thead class="bg-light text-dark">
                                        <tr>
                                            <th class="ps-3 py-2 fw-extrabold">Evrak Türü</th>
                                            <th class="py-2 fw-extrabold">Veriliş Tarihi</th>
                                            <th class="py-2 fw-extrabold">Son Geçerlilik</th>
                                            <th class="pe-3 text-end py-2 fw-extrabold">Evrak Durumu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($worker->documents as $doc)
                                            <tr>
                                                <td class="ps-3 py-2 fw-bold text-dark">
                                                    @if($doc->document_type === 'sgk_entry')
                                                        📄 SGK İşe Giriş Bildirgesi
                                                    @elseif($doc->document_type === 'isg_certificate')
                                                        📜 İSG Eğitim Katılım Sertifikası
                                                    @elseif($doc->document_type === 'health_report_ek2')
                                                        🩺 Ek-2 Ağır ve Tehlikeli İşler Sağlık Raporu
                                                    @elseif($doc->document_type === 'work_at_height')
                                                        🏗️ Yüksekte Çalışma İzin Raporu
                                                    @elseif($doc->document_type === 'vocational_cert')
                                                        🎓 MYK Mesleki Yeterlilik Belgesi
                                                    @else
                                                        📋 Diğer İSG Evrakı
                                                    @endif
                                                </td>
                                                <td class="font-monospace fw-bold text-dark">{{ $doc->issue_date ? $doc->issue_date->format('d.m.Y') : '-' }}</td>
                                                <td class="font-monospace fw-bold text-primary">{{ $doc->expiry_date ? $doc->expiry_date->format('d.m.Y') : 'Süresiz' }}</td>
                                                <td class="pe-3 text-end">
                                                    @if($doc->is_valid && !$doc->is_expired)
                                                        <span class="badge bg-success text-white rounded-pill px-2.5 py-1 fw-bold" style="font-size: 10px;">
                                                            <i class="ri-check-line me-1"></i>Geçerli
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 fw-bold" style="font-size: 10px;">
                                                            <i class="ri-error-warning-line me-1"></i>Süresi Dolmuş / Geçersiz
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted small py-3">
                                                    <i class="ri-error-warning-line text-warning me-1"></i>Henüz İSG evrakı eklenmedi. Evrak yüklenmediğinde turnike girişi engellenecektir.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Document Upload Inline Form -->
                            <form action="{{ route('subcontractor_workers.add_document', $worker->id) }}" method="POST" enctype="multipart/form-data" class="p-3 bg-light rounded-3 border">
                                @csrf
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-4">
                                        <select name="document_type" class="form-select form-select-sm rounded-pill custom-input-worker" required>
                                            <option value="sgk_entry">SGK İşe Giriş Bildirgesi</option>
                                            <option value="isg_certificate">İSG Eğitim Sertifikası</option>
                                            <option value="health_report_ek2">Ek-2 Sağlık Raporu</option>
                                            <option value="work_at_height">Yüksekte Çalışma İzni</option>
                                            <option value="vocational_cert">MYK Mesleki Yeterlilik</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="expiry_date" class="form-control form-control-sm rounded-pill custom-input-worker" placeholder="Son Geçerlilik">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="file" name="file" class="form-control form-control-sm rounded-pill custom-input-worker">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-warning w-100 rounded-pill fw-extrabold text-dark shadow-sm">
                                            <i class="ri-upload-2-line me-1"></i>Yükle
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-5 bg-white rounded-4 border">
                            <i class="ri-user-unfollow-line display-4 text-secondary mb-2 d-block"></i>
                            <h6 class="fw-bold text-dark">Henüz Taşeron Personel Kaydı Bulunmuyor</h6>
                            <p class="small text-muted mb-0">Sol taraftaki formdan taşeron firma ve personel bilgilerini girerek ilk usta/çalışan kaydınızı oluşturabilirsiniz.</p>
                        </div>
                    @endforelse

                    <div class="mt-3">
                        {{ $workers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
