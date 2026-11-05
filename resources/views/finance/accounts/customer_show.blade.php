@extends('layouts.master')

@section('title', 'Müşteri 360° Detay — ' . $customer->name)

@push('styles')
<style>
    .cs-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .cs-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: csGlow 4s linear infinite;
    }

    @keyframes csGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .cs-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-cs {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-cs:hover {
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

    .custom-input-cs {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 8px 12px !important;
    }

    .custom-input-cs:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .custom-cs-table thead,
    .custom-cs-table thead tr,
    .custom-cs-table thead th {
        background-color: #0f172a !important;
        color: #ffffff !important;
        padding: 14px 16px !important;
        font-weight: 800 !important;
        font-size: 0.85rem !important;
        border-bottom: 2px solid #d97706 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        --bs-table-bg: #0f172a !important;
        --bs-table-color: #ffffff !important;
    }

    .custom-cs-table td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 600 !important;
    }

    .nav-pills-custom .nav-link {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        font-weight: 800 !important;
        border-radius: 12px !important;
        padding: 12px 20px !important;
        border: 1.5px solid #cbd5e1 !important;
        transition: all 0.2s ease !important;
    }

    .nav-pills-custom .nav-link.active {
        background-color: #0f172a !important;
        color: #fef08a !important;
        border-color: #d97706 !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="cs-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill cs-badge-pill">
                        <i class="ri-user-star-fill text-warning fs-5"></i> CRM & FİNANS - 360° MÜŞTERİ PORTAL
                    </span>
                    @if(($customer->balance ?? 0) > 0)
                        <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                            🟡 BORÇLU (₺{{ number_format($customer->balance, 0, ',', '.') }})
                        </span>
                    @else
                        <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                            🟢 BORÇSUZ / BAKİYE TEMİZ
                        </span>
                    @endif
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">👤 {{ $customer->name }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    <i class="ri-user-tie-line text-warning me-1"></i> Yetkili: <strong class="text-white fw-bold">{{ $customer->contact_person ?? 'Belirtilmedi' }}</strong> |
                    <i class="ri-phone-line text-warning me-1"></i> Tel: <strong class="text-white fw-bold">{{ $customer->phone ?? 'Belirtilmedi' }}</strong> |
                    <i class="ri-mail-line text-warning me-1"></i> E-Posta: <strong class="text-white fw-bold">{{ $customer->email ?? 'Belirtilmedi' }}</strong>
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('finance.accounts.customers.edit', $customer->id) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-edit-fill me-1"></i>Düzenle
                    </a>
                    <form method="POST" action="{{ route('finance.accounts.customers.reconcile', ['id' => $customer->id]) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                            <i class="ri-send-plane-fill me-1"></i>Mutabakat Gönder
                        </button>
                    </form>
                    <a href="{{ route('finance.accounts.customers.reconcile.download', ['id' => $customer->id]) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-file-pdf-fill text-danger me-1"></i>Mutabakat İndir
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY FINANCIAL KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cs h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM BORÇ / BAKİYE</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.7rem;">₺{{ number_format($customer->balance ?? 0, 2, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-wallet-3-fill me-1"></i>Güncel Cari Hesap Bakiye</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-bank-card-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cs h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">VADESİ GEÇEN BORÇ</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.7rem;">₺{{ number_format($customer->overdue_balance ?? 0, 2, ',', '.') }}</div>
                        <small class="text-danger fw-bold"><i class="ri-alarm-warning-fill me-1"></i>Gecikmedeki Ödeme Bedeli</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fef2f2 !important; border: 1px solid #fecaca !important;">
                        <i class="ri-time-fill fs-2" style="color: #dc2626 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cs h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">KREDİ LİMİTİ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;">₺{{ number_format($customer->credit_limit ?? 0, 2, ',', '.') }}</div>
                        @if(($customer->credit_limit ?? 0) > 0)
                            @php $utilization = (($customer->balance ?? 0) / $customer->credit_limit) * 100; @endphp
                            <small class="text-success fw-bold"><i class="ri-percent-line me-1"></i>%{{ number_format($utilization, 1) }} kullanıldı</small>
                        @else
                            <small class="text-muted fw-bold">Limit Tanımlanmadı</small>
                        @endif
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-shield-keyhole-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cs h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">RİSK SKORU & DEĞERLENDİRME</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.7rem;">{{ $customer->risk_score ?? 0 }} / 100</div>
                        @php 
                            $riskBadge = 'bg-success text-white';
                            if(($customer->risk_score ?? 0) > 70) $riskBadge = 'bg-danger text-white';
                            elseif(($customer->risk_score ?? 0) > 40) $riskBadge = 'bg-warning text-dark';
                        @endphp
                        <span class="badge {{ $riskBadge }} rounded-pill px-2.5 py-1 font-monospace fw-bold">
                            @if(($customer->risk_score ?? 0) > 70) Yüksek Risk
                            @elseif(($customer->risk_score ?? 0) > 40) Orta Risk
                            @else Düşük Risk Risk Yok
                            @endif
                        </span>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-shield-cross-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TABS CONTAINER -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark p-3.5" style="background-color: #0f172a !important;">
            <ul class="nav nav-pills nav-pills-custom gap-2 border-0" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="pill" href="#overview" role="tab">
                        <i class="ri-dashboard-fill me-1"></i>Genel Bakış & Tahsilat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#statement" role="tab">
                        <i class="ri-file-list-3-fill me-1"></i>Hesap Ekstresi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#ageing" role="tab">
                        <i class="ri-time-fill me-1"></i>Borç Yaşlandırma
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#profile" role="tab">
                        <i class="ri-user-settings-fill me-1"></i>Kurumsal Bilgiler
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#activity" role="tab">
                        <i class="ri-history-fill me-1"></i>Mutabakat Geçmişi
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content">

                <!-- OVERVIEW TAB -->
                <div class="tab-pane show active" id="overview" role="tabpanel">
                    <!-- Quick Collection Input -->
                    <div class="p-4 rounded-3 border mb-4" style="background: #f8fafc; border-left: 4px solid #d97706 !important;">
                        <h5 class="fw-extrabold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="ri-flashlight-fill text-warning fs-5"></i>Hızlı Tahsilat Girişi
                        </h5>
                        <form method="POST" action="{{ route('finance.accounts.invoices.receipts.store.body') }}" class="row g-3 align-items-end">
                            @csrf
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark small">Açık Fatura Seçimi</label>
                                <select name="invoice_id" class="form-select custom-input-cs">
                                    @php
                                        $invoicesSelect = [];
                                        if(class_exists(\Illuminate\Support\Facades\Schema::class) && 
                                           \Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                                            $invoicesSelect = $customer->invoices()->whereRaw('amount - COALESCE(paid_amount, 0) > 0')->get();
                                        }
                                    @endphp
                                    @forelse($invoicesSelect as $inv)
                                        <option value="{{ $inv->id }}">
                                            {{ $inv->no }} - Kalan: {{ number_format($inv->amount - ($inv->paid_amount ?? 0), 2, ',', '.') }} ₺
                                        </option>
                                    @empty
                                        <option value="">Açık fatura bulunamadı</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-dark small">Ödeme Tarihi</label>
                                <input name="date" type="date" class="form-control custom-input-cs" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-dark small">Tahsilat Tutarı (₺)</label>
                                <input name="amount" type="number" step="0.01" class="form-control custom-input-cs" placeholder="0,00">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-dark small">Ödeme Yöntemi</label>
                                <select name="method" class="form-select custom-input-cs">
                                    <option value="Nakit">💵 Nakit</option>
                                    <option value="Havale">🏦 Havale / EFT</option>
                                    <option value="Kredi Kartı">💳 Kredi Kartı</option>
                                    <option value="Çek">📜 Çek / Senet</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-dark small">Referans No</label>
                                <input name="reference" class="form-control custom-input-cs" placeholder="Dekont / Çek No">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-warning text-dark fw-extrabold w-100 py-2 shadow-sm">
                                    <i class="ri-check-line fs-5"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Customer Quick Summary Grid -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 rounded-3 border" style="background: #ffffff; border: 1.5px solid #cbd5e1 !important;">
                                <h6 class="fw-extrabold text-dark mb-3"><i class="ri-information-fill text-primary me-1"></i>Cari Hesap Özet Bilgileri</h6>
                                <table class="table table-sm align-middle mb-0">
                                    <tbody>
                                        <tr><td class="text-muted fw-bold">Unvan:</td><td class="fw-extrabold text-dark">{{ $customer->name }}</td></tr>
                                        <tr><td class="text-muted fw-bold">Vergi No:</td><td class="font-monospace fw-bold">{{ $customer->tax_number ?? '-' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">Vergi Dairesi:</td><td class="fw-bold">{{ $customer->tax_office ?? '-' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">İletişim Tel:</td><td class="font-monospace fw-bold text-primary">{{ $customer->phone ?? '-' }}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-4 rounded-3 border" style="background: #ffffff; border: 1.5px solid #cbd5e1 !important;">
                                <h6 class="fw-extrabold text-dark mb-3"><i class="ri-bank-card-fill text-success me-1"></i>Banka & Finans Bilgileri</h6>
                                <table class="table table-sm align-middle mb-0">
                                    <tbody>
                                        <tr><td class="text-muted fw-bold">Banka:</td><td class="fw-bold">{{ $customer->bank ?? '-' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">IBAN:</td><td class="font-monospace fw-bold text-dark">{{ $customer->iban ?? '-' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">Risk Seviyesi:</td><td class="fw-bold"><span class="badge bg-warning text-dark">{{ $customer->risk_score ?? 0 }}/100</span></td></tr>
                                        <tr><td class="text-muted fw-bold">Vadesi Geçen:</td><td class="font-monospace fw-extrabold text-danger">₺{{ number_format($customer->overdue_balance ?? 0, 2, ',', '.') }}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STATEMENT TAB -->
                <div class="tab-pane" id="statement" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-extrabold text-dark mb-0"><i class="ri-file-list-3-line text-primary me-2"></i>Hesap Ekstresi Detayı</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table custom-cs-table align-middle mb-0">
                            <thead style="background-color: #0f172a !important;">
                                <tr style="background-color: #0f172a !important;">
                                    <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Tarih</th>
                                    <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Tip</th>
                                    <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Açıklama / Belge</th>
                                    <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Borç (₺)</th>
                                    <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Alacak (₺)</th>
                                    <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Bakiye (₺)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $runningBalance = 0; @endphp
                                @forelse($statement as $s)
                                    @php
                                        $isDebit = in_array($s['type'], ['Invoice']);
                                        $amount = $s['amount'];
                                        $runningBalance += $isDebit ? $amount : -$amount;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 font-monospace fw-bold">{{ $s['date'] ? \Carbon\Carbon::parse($s['date'])->format('d.m.Y') : '-' }}</td>
                                        <td>
                                            @if($s['type'] == 'Invoice')
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #ef4444 !important;">Fatura</span>
                                            @elseif($s['type'] == 'Receipt')
                                                <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #16a34a !important;">Tahsilat</span>
                                            @else
                                                <span class="badge bg-secondary text-white rounded-pill px-3 py-1 font-monospace fw-bold">{{ $s['type'] }}</span>
                                            @endif
                                        </td>
                                        <td><span class="fw-bold text-dark">{{ $s['notes'] }}</span></td>
                                        <td class="text-end font-monospace">
                                            @if($isDebit)
                                                <strong class="text-danger">₺{{ number_format($amount, 2, ',', '.') }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end font-monospace">
                                            @if(!$isDebit)
                                                <strong class="text-success">₺{{ number_format(abs($amount), 2, ',', '.') }}</strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end font-monospace">
                                            <strong class="{{ $runningBalance > 0 ? 'text-danger' : 'text-success' }}" style="font-size: 1.05rem;">
                                                ₺{{ number_format($runningBalance, 2, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="ri-file-search-line display-4 text-warning mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Henüz Hesap Ekstresi Kaydı Bulunmuyor</h6>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- AGEING TAB -->
                <div class="tab-pane" id="ageing" role="tabpanel">
                    <h5 class="fw-extrabold text-dark mb-3"><i class="ri-time-line text-warning me-2"></i>Borç Yaşlandırma Dilimleri</h5>
                    
                    <div class="row g-3 mb-4">
                        @foreach($ageing as $period => $amount)
                            <div class="col-md-3">
                                <div class="p-3.5 rounded-3 border text-center" style="background: #ffffff; border: 1.5px solid #cbd5e1 !important;">
                                    <small class="text-muted fw-bold text-uppercase d-block mb-1 font-monospace" style="font-size: 11px;">{{ $period }} Gün</small>
                                    <h4 class="fw-extrabold font-monospace mb-0 text-{{ $period == '91+' ? 'danger' : ($period == '61-90' ? 'warning' : 'success') }}">
                                        ₺{{ number_format($amount, 2, ',', '.') }}
                                    </h4>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(isset($ageingDetails) && count($ageingDetails) > 0)
                        <div class="table-responsive">
                            <table class="table custom-cs-table align-middle mb-0">
                                <thead style="background-color: #0f172a !important;">
                                    <tr style="background-color: #0f172a !important;">
                                        <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Fatura No</th>
                                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Fatura Tarihi</th>
                                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Vade Tarihi</th>
                                        <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Tutar</th>
                                        <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Ödenen</th>
                                        <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Kalan Bakiye</th>
                                        <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Gecikme</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ageingDetails as $detail)
                                        <tr>
                                            <td class="ps-4 font-monospace fw-extrabold text-dark">{{ $detail['invoice_no'] }}</td>
                                            <td class="font-monospace">{{ $detail['date'] ? \Carbon\Carbon::parse($detail['date'])->format('d.m.Y') : '-' }}</td>
                                            <td class="font-monospace">{{ $detail['due_date'] ? \Carbon\Carbon::parse($detail['due_date'])->format('d.m.Y') : '-' }}</td>
                                            <td class="text-end font-monospace fw-bold">₺{{ number_format($detail['amount'], 2, ',', '.') }}</td>
                                            <td class="text-end font-monospace text-success fw-bold">₺{{ number_format($detail['paid'], 2, ',', '.') }}</td>
                                            <td class="text-end font-monospace text-danger fw-extrabold">₺{{ number_format($detail['balance'], 2, ',', '.') }}</td>
                                            <td class="text-center font-monospace">
                                                @if($detail['days_overdue'] > 0)
                                                    <span class="badge bg-danger text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #ef4444 !important;">{{ $detail['days_overdue'] }} Gün Gecikme</span>
                                                @else
                                                    <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #16a34a !important;">Vade İçi</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- PROFILE TAB -->
                <div class="tab-pane" id="profile" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="p-4 rounded-3 border" style="background: #ffffff; border: 1.5px solid #cbd5e1 !important;">
                                <h5 class="fw-extrabold text-dark mb-3"><i class="ri-user-star-line text-primary me-2"></i>Temel Kurumsal Bilgiler</h5>
                                <table class="table table-borderless align-middle mb-0">
                                    <tbody>
                                        <tr><td class="text-muted fw-bold">Firma Unvanı:</td><td class="fw-extrabold text-dark fs-6">{{ $customer->name }}</td></tr>
                                        <tr><td class="text-muted fw-bold">Vergi Numarası:</td><td class="font-monospace fw-bold">{{ $customer->tax_number ?? '-' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">Vergi Dairesi:</td><td class="fw-bold">{{ $customer->tax_office ?? '-' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">MERSİS No:</td><td class="font-monospace fw-bold">{{ $customer->mersis ?? '-' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">Yetkili İrtibat Kişisi:</td><td class="fw-extrabold text-dark">{{ $customer->contact_person ?? '-' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">Telefon:</td><td class="font-monospace fw-extrabold text-primary">{{ $customer->phone ?? '-' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">E-Posta:</td><td class="font-monospace fw-bold text-dark">{{ $customer->email ?? '-' }}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="p-4 rounded-3 border" style="background: #ffffff; border: 1.5px solid #cbd5e1 !important;">
                                <h5 class="fw-extrabold text-dark mb-3"><i class="ri-map-pin-line text-success me-2"></i>Adres & Banka Hesap Bilgileri</h5>
                                <table class="table table-borderless align-middle mb-0">
                                    <tbody>
                                        <tr><td class="text-muted fw-bold">Adres:</td><td class="fw-bold text-dark">{{ $customer->address ?? '-' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">Şehir / İlçe:</td><td class="fw-bold">{{ ($customer->city ?? '') . ' / ' . ($customer->district ?? '') }}</td></tr>
                                        <tr><td class="text-muted fw-bold">Ülke:</td><td class="fw-bold">{{ $customer->country ?? 'Türkiye' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">IBAN:</td><td class="font-monospace fw-bold text-dark">{{ $customer->iban ?? '-' }}</td></tr>
                                        <tr><td class="text-muted fw-bold">Banka:</td><td class="fw-bold">{{ $customer->bank ?? '-' }}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTIVITY TAB -->
                <div class="tab-pane" id="activity" role="tabpanel">
                    <h5 class="fw-extrabold text-dark mb-3"><i class="ri-history-line text-info me-2"></i>Mutabakat Geçmişi & Log Kayıtları</h5>
                    @if($logs && $logs->count())
                        <div class="table-responsive">
                            <table class="table custom-cs-table align-middle mb-0">
                                <thead style="background-color: #0f172a !important;">
                                    <tr style="background-color: #0f172a !important;">
                                        <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Tarih</th>
                                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Eylem</th>
                                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Kullanıcı</th>
                                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">E-Posta</th>
                                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Dosya</th>
                                        <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">IP Adresi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr>
                                            <td class="ps-4 font-monospace fw-bold">{{ $log->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-primary text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                                                    {{ ucfirst($log->action) }}
                                                </span>
                                            </td>
                                            <td><strong class="text-dark">👤 {{ optional($log->user)->name ?? 'Sistem' }}</strong></td>
                                            <td class="font-monospace"><small class="text-muted">{{ $log->email }}</small></td>
                                            <td><small class="text-muted font-monospace">{{ $log->filename ?? '-' }}</small></td>
                                            <td class="font-monospace"><small class="text-muted">{{ $log->ip }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="ri-history-line display-4 text-warning mb-2 d-block"></i>
                            <h6 class="fw-bold text-dark">Henüz Mutabakat Log Kaydı Bulunmuyor</h6>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

</div>
@endsection