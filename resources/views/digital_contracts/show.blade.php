@extends('layouts.master')

@section('title', 'Sözleşme Detayı — ' . ($contract->title ?? $contract->name))

@push('styles')
<style>
    .dc-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .dc-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: dcShowGlow 4s linear infinite;
    }

    @keyframes dcShowGlow {
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

    .custom-kpi-card-dcshow {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-dcshow:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-show-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .custom-input-dc {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
    }

    .custom-input-dc:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="dc-show-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    @if($contract->status == 'active')
                        <span class="badge bg-success text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #16a34a !important;">🟢 AKTİF / YÜRÜRLÜKTE</span>
                    @elseif($contract->status == 'draft')
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 font-monospace fw-bold">🟡 TASLAK SÖZLEŞME</span>
                    @else
                        <span class="badge bg-secondary text-white rounded-pill px-3 py-2 font-monospace fw-bold">⚪ {{ strtoupper($contract->status) }}</span>
                    @endif
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill dc-badge-pill font-monospace">
                        <i class="ri-hashtag text-warning fs-5"></i> {{ $contract->number ?? ('DC-' . str_pad($contract->id, 4, '0', STR_PAD_LEFT)) }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">📜 {{ $contract->title ?? $contract->name }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    <i class="ri-building-line text-warning me-1"></i> Proje: <strong class="text-white fw-bold">{{ $contract->project?->name ?? 'Bağımsız Sözleşme' }}</strong>
                    @if($contract->company)
                        | Şirket: <strong class="text-white fw-bold">{{ $contract->company }}</strong>
                    @endif
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('digital-contracts.pdf', $contract) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-file-pdf-fill text-danger me-1"></i>Belgeyi İndir
                    </a>
                    <a href="{{ route('digital-contracts.edit', $contract) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-edit-fill me-1"></i>Düzenle
                    </a>
                    <a href="{{ route('digital-contracts.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Sözleşmeler Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FINANCIAL & LEGAL SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-dcshow h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">SÖZLEŞME TUTARI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.7rem;">₺{{ number_format($contract->value ?? 0, 0, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Hukuki Sözleşme Bedeli</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-dcshow h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">YÜRÜRLÜK TARİHİ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;">
                            {{ $contract->effective_date ? \Carbon\Carbon::parse($contract->effective_date)->format('d.m.Y') : '-' }}
                        </div>
                        <small class="text-success fw-bold"><i class="ri-calendar-check-fill me-1"></i>Geçerlilik Başlangıcı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-calendar-check-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-dcshow h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">BİTİŞ / FESİH TARİHİ</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.5rem;">
                            {{ $contract->expiration_date ? \Carbon\Carbon::parse($contract->expiration_date)->format('d.m.Y') : 'Süresiz' }}
                        </div>
                        <small class="text-warning fw-bold"><i class="ri-calendar-event-line me-1"></i>Zaman Aşımı / Vade</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-time-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN DETAILS GRID -->
    <div class="row">
        <!-- LEFT COLUMN: CONTENT & SIGNATURES -->
        <div class="col-lg-8">
            <!-- CONTRACT TEXT CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-article-line text-warning fs-5"></i>Sözleşme Metni ve Şartname
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="p-4 rounded-3 text-dark fw-semibold" style="background: #f8fafc; border: 1px solid #cbd5e1; line-height: 1.8; white-space: pre-wrap;">
                        {!! $contract->content !!}
                    </div>
                </div>
            </div>

            <!-- E-SIGNATURE STATUS TABLE -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-shield-check-fill text-warning fs-5"></i>E-İmza Ve Onay Takip Durumu
                    </h5>
                    <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                        {{ $contract->signatures->where('status', 'signed')->count() }} / {{ $contract->signatures->count() }} İmzalandı
                    </span>
                </div>

                <div class="card-body p-0">
                    @if($contract->signatures->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead style="background-color: #0f172a !important; color: #ffffff !important;">
                                    <tr style="background-color: #0f172a !important;">
                                        <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">İmza Sahibi</th>
                                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Unvan / Rol</th>
                                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">E-Posta</th>
                                        <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Durum</th>
                                        <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Tarih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contract->signatures as $sig)
                                        <tr>
                                            <td class="ps-4 py-3"><strong class="text-dark fs-6">👤 {{ $sig->signer_name }}</strong></td>
                                            <td><span class="badge bg-secondary text-white rounded-pill px-3 py-1 fw-bold">{{ $sig->signer_role }}</span></td>
                                            <td><small class="text-muted font-monospace fw-semibold">{{ $sig->signer_email ?? $sig->email }}</small></td>
                                            <td class="text-center font-monospace">
                                                @if($sig->status == 'signed')
                                                    <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">
                                                        <i class="ri-checkbox-circle-fill me-1"></i>İmzalandı
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">
                                                        <i class="ri-time-line me-1"></i>Bekliyor
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="pe-4 text-end font-monospace"><small class="text-muted fw-bold">{{ $sig->signed_at ? $sig->signed_at->format('d.m.Y H:i') : '-' }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="ri-information-line fs-3 text-warning mb-1 d-block"></i>
                            <span class="fw-bold text-dark">Bu sözleşme için henüz e-imza daveti tanımlanmamıştır.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: SIGNATURE INVITATION & META -->
        <div class="col-lg-4">
            <!-- SIGNATURE REQUEST FORM CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-mail-send-fill text-warning fs-5"></i>E-İmza Daveti Gönder
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('digital-contracts.signature_request', $contract) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-extrabold text-dark small">İmzalayacak Kişi Adı Soyadı</label>
                            <input type="text" name="signer_name" class="form-control custom-input-dc" placeholder="Örn: Ahmet Yılmaz" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-extrabold text-dark small">E-Posta Adresi</label>
                            <input type="email" name="signer_email" class="form-control custom-input-dc" placeholder="ahmet@firma.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-extrabold text-dark small">Kurumsal Pozisyon / Rol</label>
                            <input type="text" name="signer_role" class="form-control custom-input-dc" placeholder="Örn: Genel Müdür / Müşteri" required>
                        </div>

                        <button type="submit" class="btn btn-warning text-dark w-100 rounded-pill py-2.5 fw-extrabold shadow-sm mt-2">
                            <i class="ri-send-plane-fill me-1"></i>İmza Davetiyesi Gönder
                        </button>
                    </form>
                </div>
            </div>

            <!-- META & DELETE ACTIONS CARD -->
            <div class="card custom-show-card">
                <div class="card-body p-4">
                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Sistem Kayıt Tarihi</small>
                    <span class="fw-bold text-dark d-block mb-3 font-monospace">📅 {{ $contract->created_at ? $contract->created_at->format('d.m.Y H:i') : '-' }}</span>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('digital-contracts.edit', $contract) }}" class="btn btn-warning text-dark rounded-pill py-2.5 fw-extrabold shadow-sm">
                            <i class="ri-edit-fill me-1"></i>Sözleşmeyi Düzenle
                        </a>
                        <form action="{{ route('digital-contracts.destroy', $contract) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill py-2.5 fw-extrabold" onclick="return confirmDelete(this, 'Dijital Sözleşmeyi Sil?', 'Bu sözleşme kaydı kalıcı olarak silinecektir.')">
                                <i class="ri-delete-bin-line me-1"></i>Sözleşmeyi Sil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
