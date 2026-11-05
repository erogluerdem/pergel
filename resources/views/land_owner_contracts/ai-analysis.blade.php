@extends('layouts.master')

@section('title', 'AI Kat Karşılığı Sözleşme & Risk Analizi')

@push('styles')
<style>
    .ai-hero-banner {
        background: linear-gradient(135deg, #2e1065 0%, #1e1b4b 60%, #4c1d95 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(167, 139, 250, 0.3) !important;
        box-shadow: 0 15px 35px rgba(46, 16, 101, 0.4) !important;
    }
    
    .ai-pulse-badge {
        background: rgba(167, 139, 250, 0.2) !important;
        border: 1px solid rgba(167, 139, 250, 0.4) !important;
        color: #ddd6fe !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .ai-score-ring {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        background: #ffffff;
    }

    .ai-summary-box {
        background: #f5f3ff !important;
        border: 1.5px solid #ddd6fe !important;
        border-radius: 16px !important;
        color: #4c1d95 !important;
        font-size: 0.95rem !important;
        line-height: 1.65 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Futuristic AI Hero Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 ai-hero-banner p-4 p-md-5 text-white">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 ai-pulse-badge">
                            <i class="ri-robot-3-line text-warning fs-5"></i>
                            <span class="text-uppercase" style="font-size: 11px;">YAPAY ZEKA HUKUK & RİSK ANALİZÖRÜ</span>
                        </div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">Kat Karşılığı Sözleşme Risk Analizi</h2>
                        <p class="text-white-50 mb-0 fs-6" style="max-width: 700px; line-height: 1.6;">
                            Arsa sahibi kat karşılığı inşaat sözleşmelerindeki paylaşım oranlarını, cezai şartları, tapu devir maddelerini ve hukuki riskleri yapay zeka ile otomatik analiz edin.
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('land_owner_contracts.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                            <i class="ri-file-list-3-line"></i>Sözleşmeler Listesi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Selector Bar -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border">
                <form action="{{ route('land_owner_contracts.ai_analysis') }}" method="GET" class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                    <label class="fw-extrabold text-dark mb-0 text-nowrap"><i class="ri-file-search-line me-1 text-primary"></i>İncelenecek Sözleşme Seçin:</label>
                    <select name="contract_id" class="form-select rounded-pill custom-filter-input py-2 fw-bold text-dark" onchange="this.form.submit()">
                        @forelse($contracts as $c)
                            <option value="{{ $c->id }}" {{ ($selectedContract && $selectedContract->id == $c->id) ? 'selected' : '' }}>
                                {{ $c->land_owner_name }} - {{ $c->project->name ?? 'Proje' }} (%{{ $c->land_share_percentage ?? 50 }} Arsa Sahibi Payı)
                            </option>
                        @empty
                            <option value="">Kayıtlı Sözleşme Bulunmuyor</option>
                        @endforelse
                    </select>
                </form>
            </div>
        </div>
    </div>

    @if($selectedContract && $aiAnalysis)
        <div class="row g-4 mb-4">
            <!-- Left Side: AI Risk Score Radar & Share Matrix -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 text-center p-4 bg-white h-100 border">
                    <span class="badge bg-primary-subtle text-primary border rounded-pill px-3 py-1.5 fw-extrabold align-self-center mb-4" style="font-size: 11px;">
                        <i class="ri-shield-flash-line me-1"></i>AI RİSK İNDEKSİ
                    </span>

                    <div class="ai-score-ring mb-3" style="border: 8px solid {{ $aiAnalysis['risk_color'] }};">
                        <span class="display-4 fw-extrabold" style="color: {{ $aiAnalysis['risk_color'] }}; line-height: 1;">{{ $aiAnalysis['risk_score'] }}</span>
                        <small class="text-muted fw-bold font-monospace mt-1" style="font-size: 10px;">/ 100 RİSK</small>
                    </div>

                    <h4 class="fw-extrabold mb-1" style="color: {{ $aiAnalysis['risk_color'] }};">{{ $aiAnalysis['risk_level'] }}</h4>
                    <p class="text-muted small mb-4">Sözleşme şartları ve bağımsız bölüm paylaşım risk seviyesi.</p>

                    <!-- Share Matrix Breakdowns -->
                    <div class="p-3 bg-light rounded-4 text-start border">
                        <small class="fw-extrabold text-dark d-block mb-3 text-uppercase tracking-wider" style="font-size: 11px;">
                            <i class="ri-pie-chart-2-fill text-primary me-1"></i>PAYLAŞIM MATRİSİ ORANLARI
                        </small>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold text-dark">Arsa Sahibi Payı:</small>
                                <small class="fw-extrabold text-warning">%{{ $aiAnalysis['landowner_share'] }}</small>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px; background: #e2e8f0;">
                                <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: {{ $aiAnalysis['landowner_share'] }}%;"></div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold text-dark">Müteahhit Payı:</small>
                                <small class="fw-extrabold text-primary">%{{ $aiAnalysis['contractor_share'] }}</small>
                            </div>
                            <div class="progress rounded-pill" style="height: 8px; background: #e2e8f0;">
                                <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: {{ $aiAnalysis['contractor_share'] }}%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Independent Units Matrix -->
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <div class="p-2.5 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25 text-center">
                                <small class="text-dark d-block fw-extrabold" style="font-size: 10px;">ARSA SAHİBİ DAİRE</small>
                                <span class="fw-extrabold text-dark fs-5 font-monospace">{{ $aiAnalysis['landowner_units'] }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2.5 rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 text-center">
                                <small class="text-dark d-block fw-extrabold" style="font-size: 10px;">MÜTEAHHİT DAİRE</small>
                                <span class="fw-extrabold text-dark fs-5 font-monospace">{{ $aiAnalysis['contractor_units'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: AI Executive Report & Risk Warnings -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                            <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="ri-magic-fill text-purple fs-4"></i>Yapay Zeka Analiz Raporu
                            </h5>
                            <span class="badge bg-secondary-subtle text-dark font-monospace fw-bold">Sözleşme ID: #{{ $selectedContract->id }}</span>
                        </div>

                        <!-- AI Executive Summary Box -->
                        <div class="p-4 ai-summary-box mb-4 shadow-sm">
                            <i class="ri-chat-quote-line text-purple fs-4 me-2"></i>
                            <strong>Hukuki AI Değerlendirmesi:</strong>
                            <p class="mb-0 mt-2">{{ $aiAnalysis['ai_summary'] }}</p>
                        </div>

                        <h6 class="fw-extrabold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="ri-error-warning-fill text-warning fs-5"></i>Tespit Edilen Maddeler & Risk Uyarısı
                        </h6>

                        <!-- Risk Flag Alerts -->
                        @forelse($aiAnalysis['risk_flags'] as $flag)
                            <div class="p-3 mb-3 rounded-4 border-start border-4 border-{{ $flag['type'] }} bg-light border shadow-sm">
                                <h6 class="fw-extrabold text-dark mb-1 d-flex align-items-center gap-2">
                                    @if($flag['type'] === 'danger')
                                        <i class="ri-alarm-warning-line text-danger"></i>
                                    @elseif($flag['type'] === 'warning')
                                        <i class="ri-alert-line text-warning"></i>
                                    @else
                                        <i class="ri-information-line text-info"></i>
                                    @endif
                                    {{ $flag['title'] }}
                                </h6>
                                <p class="text-secondary small mb-0 fw-medium" style="line-height: 1.5;">{{ $flag['desc'] }}</p>
                            </div>
                        @empty
                            <div class="p-4 bg-success bg-opacity-10 text-success rounded-4 border border-success border-opacity-25 d-flex align-items-center gap-3">
                                <i class="ri-checkbox-circle-fill fs-2"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Kritik Risk Tespit Edilmedi</h6>
                                    <p class="mb-0 small">Bu kat karşılığı inşaat sözleşmesinde standart dışı ağır bir risk veya dengesiz paylaşım maddesi tespit edilmemiştir.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Bottom Action Buttons -->
                    <div class="pt-3 border-top d-flex gap-2 justify-content-end">
                        <button type="button" onclick="window.print()" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold">
                            <i class="ri-printer-line me-1"></i>Raporu Yazdır / PDF
                        </button>
                        <a href="{{ route('land_owner_contracts.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                            <i class="ri-check-line me-1"></i>Sözleşmeye Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
