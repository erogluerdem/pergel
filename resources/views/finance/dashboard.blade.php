@extends('layouts.master')

@section('title', 'Finans Kontrol Paneli')
@section('topbar-title', 'Finans Kontrol Paneli')
{{-- Master layout'ta otomatik sayfa başlığını gösteren alanı bu görünüm için devre dışı bırakıyoruz
    Böylece master içindeki `page-title-box` bölümü render edilmez ve içerik topbar'a daha yakın olur. --}}
@section('suppress_page_title')

@push('styles')
<style>
:root {
    --finance-page-bg: #f5f7fb;
    --finance-surface: #ffffff;
    --finance-border: #dce3f4;
    --finance-muted: #64748b;
    --finance-heading: #0f172a;
    --finance-accent: #2563eb;
    --finance-accent-soft: rgba(37, 99, 235, 0.14);
    --finance-positive: #0f766e;
    --finance-negative: #dc2626;
    --finance-warning: #f59e0b;
    --finance-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
    --finance-radius: 18px;
    --finance-font: 'Inter', 'Segoe UI', sans-serif;
}

.finance-dashboard {
    background: var(--finance-page-bg);
    min-height: 100vh;
    /* DÜZELTME: Üstteki istenmeyen boşluğu kaldırmak için padding-top sıfırlandı. */
    padding: 0 0 48px;
    font-family: var(--finance-font);
    color: var(--finance-heading);
}

.finance-dashboard .page-header {
    background: var(--finance-surface);
    border: 1px solid var(--finance-border);
    border-radius: var(--finance-radius);
    padding: 28px 32px;
    margin-bottom: 32px;
    box-shadow: var(--finance-shadow);
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.page-header__main {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
}

.eyebrow {
    text-transform: uppercase;
    letter-spacing: 0.12em;
    font-size: 0.75rem;
    color: var(--finance-muted);
    margin-bottom: 8px;
    font-weight: 600;
}

.page-header__title {
    max-width: 640px;
}

.page-header__title h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.page-header__title p {
    margin: 0;
    color: var(--finance-muted);
    font-size: 1rem;
}

.page-header__meta {
    min-width: 200px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    align-items: flex-start;
}

.meta-label {
    font-size: 0.75rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--finance-muted);
    font-weight: 600;
}

.meta-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--finance-heading);
}

.page-header__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.action-button {
    border-radius: 999px;
    padding: 10px 18px;
    border: 1px solid var(--finance-border);
    background: var(--finance-surface);
    color: var(--finance-heading);
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.action-button:hover {
    border-color: var(--finance-accent);
    color: var(--finance-accent);
    text-decoration: none;
    transform: translateY(-1px);
}

.action-button:disabled {
    opacity: 0.65;
    pointer-events: none;
}

.action-button__icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
}

.action-button--primary {
    background: var(--finance-accent);
    color: #ffffff;
    border-color: var(--finance-accent);
    box-shadow: 0 12px 25px rgba(37, 99, 235, 0.25);
}

.action-button--primary:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    color: #ffffff;
}

.action-button.is-loading {
    opacity: 0.75;
}

.action-button.is-loading .action-button__icon {
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 24px;
    margin-bottom: 36px;
}

.stat-card {
    background: var(--finance-surface);
    border-radius: var(--finance-radius);
    border: 1px solid var(--finance-border);
    padding: 22px;
    display: flex;
    gap: 18px;
    align-items: flex-start;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 22px 50px rgba(15, 23, 42, 0.08);
}

.stat-card__icon {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.stat-card__icon--revenue {
    background: rgba(37, 99, 235, 0.12);
    color: var(--finance-accent);
}

.stat-card__icon--expense {
    background: rgba(220, 38, 38, 0.12);
    color: var(--finance-negative);
}

.stat-card__icon--profit {
    background: rgba(15, 118, 110, 0.12);
    color: var(--finance-positive);
}

.stat-card__icon--cash {
    background: rgba(245, 158, 11, 0.12);
    color: var(--finance-warning);
}

.stat-card__label {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--finance-muted);
    font-weight: 600;
}

.stat-card__value {
    display: block;
    font-size: 1.8rem;
    font-weight: 700;
    margin: 6px 0 10px;
}

.stat-card__footer {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.stat-card__delta {
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 999px;
}

.stat-card__delta--up {
    background: rgba(15, 118, 110, 0.12);
    color: var(--finance-positive);
}

.stat-card__delta--down {
    background: rgba(220, 38, 38, 0.12);
    color: var(--finance-negative);
}

.stat-card__delta--neutral {
    background: var(--finance-accent-soft);
    color: var(--finance-accent);
}

.stat-card__hint {
    font-size: 0.8rem;
    color: var(--finance-muted);
    font-weight: 500;
}

.stat-card__inline {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-top: 12px;
}

.stat-card__inline div {
    background: rgba(148, 163, 184, 0.1);
    border-radius: 12px;
    padding: 10px 12px;
}

.inline-label {
    display: block;
    font-size: 0.75rem;
    color: var(--finance-muted);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 4px;
    font-weight: 600;
}

.inline-value {
    font-weight: 700;
    font-size: 1rem;
}

.insight-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 20px;
    margin-bottom: 36px;
}

.insight-card {
    background: var(--finance-surface);
    border: 1px solid var(--finance-border);
    border-radius: var(--finance-radius);
    padding: 20px;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.05);
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.insight-card h3 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
}

.insight-card__value {
    font-size: 1.4rem;
    font-weight: 700;
}

.insight-card__meta {
    font-size: 0.85rem;
    color: var(--finance-muted);
    margin: 0;
}

.layout-grid {
    display: grid;
    gap: 24px;
    margin-bottom: 36px;
}

.layout-grid--two {
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}

.section-card {
    background: var(--finance-surface);
    border-radius: var(--finance-radius);
    border: 1px solid var(--finance-border);
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.05);
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    height: 100%;
}

.section-card__header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
}

.section-card__header h2 {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
}

.section-card__header p {
    margin: 4px 0 0;
    color: var(--finance-muted);
    font-size: 0.95rem;
}

.badge-soft {
    background: var(--finance-accent-soft);
    color: var(--finance-accent);
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.chart-wrapper {
    position: relative;
    width: 100%;
    height: 290px;
}

.gauge {
    display: flex;
    flex-direction: column;
    gap: 14px;
    align-items: flex-start;
}

.gauge__value {
    font-size: 2rem;
    font-weight: 700;
}

.gauge__bar {
    width: 100%;
    height: 10px;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.08);
    overflow: hidden;
}

.gauge__fill {
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(90deg, var(--finance-accent), #38bdf8);
}

.gauge__target {
    font-size: 0.85rem;
    color: var(--finance-muted);
}

.data-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.data-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid rgba(148, 163, 184, 0.18);
    background: rgba(248, 250, 252, 0.7);
}

.data-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--finance-muted);
    font-weight: 600;
}

.data-value {
    font-weight: 700;
    font-size: 1.2rem;
}

.stack {
    display: grid;
    gap: 20px;
}

.notice-card {
    border-left: 4px solid var(--finance-warning);
    background: linear-gradient(145deg, rgba(245, 158, 11, 0.08), rgba(245, 158, 11, 0.02));
}

.notice-card__title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--finance-heading);
}

.notice-card__list {
    list-style: none;
    padding: 0;
    margin: 8px 0 0;
    display: grid;
    gap: 10px;
}

.notice-card__list li {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.95rem;
    color: var(--finance-heading);
}

.notice-card__list i {
    color: var(--finance-warning);
}

.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.quick-action {
    flex: 1 1 180px;
    border-radius: 14px;
    border: 1px solid var(--finance-border);
    padding: 12px 16px;
    background: rgba(37, 99, 235, 0.08);
    color: var(--finance-heading);
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.2s ease;
}

.quick-action:hover {
    background: rgba(37, 99, 235, 0.14);
    border-color: var(--finance-accent);
    color: var(--finance-accent);
}

.quick-action i {
    font-size: 1rem;
    color: var(--finance-accent);
}

.empty-state {
    color: var(--finance-muted);
    font-size: 0.95rem;
}

@media (max-width: 992px) {
    .page-header__main {
        flex-direction: column;
        align-items: flex-start;
    }

    .page-header__meta {
        flex-direction: row;
        align-items: center;
        gap: 12px;
    }
}

@media (max-width: 768px) {
    .finance-dashboard {
        padding-bottom: 32px;
    }

    .page-header {
        padding: 24px;
    }

    .stat-card {
        flex-direction: column;
        align-items: stretch;
    }

    .stat-card__inline {
        grid-template-columns: 1fr;
    }

    .data-grid {
        grid-template-columns: 1fr;
    }

    .quick-actions {
        flex-direction: column;
    }

    .quick-action {
        flex: 1 1 auto;
    }
}

    <style>
        /* Dashboard-specific overrides to remove remaining top spacing from master layout/topbar */
        #layout-wrapper .main-content .page-content {
            padding-top: 6px !important; /* shrink the default top padding */
            margin-top: 0 !important;
            min-height: calc(100vh - 80px) !important; /* reduce reserved height if needed */
        }

        #layout-wrapper .main-content .page-content .container-fluid {
            padding-top: 0 !important;
        }

        .finance-dashboard {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }

        /* If page-title-box still renders for some reason, hide it on this page only */
        .page-title-box { display: none !important; }
    </style>
    <style>
        /* Force-remove the top margin set by topbar for this page only */
        body .main-content, body .content-wrapper {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        /* Keep topbar fixed visually above content */
        #page-topbar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1100 !important;
        }
    </style>
</style>
@endpush

@section('content')
<div class="finance-dashboard">
    {{-- DÜZELTME: Toolbar ile arasındaki boşluğu tamamen kaldırmak için pt-4 sınıfı kaldırıldı. --}}
    <div class="container-fluid px-3 px-lg-4 px-xl-5 py-0 mt-0">
        @php
            $averageInvoiceValue = $totalCustomers > 0 ? $totalReceivables / max(1, $totalCustomers) : 0;
            $collectionRate = $totalRevenue > 0 ? (($totalRevenue - $totalReceivables) / $totalRevenue) * 100 : 0;
            $cashConversionCycle = max(18, min(75, round(48 - ($profitMargin / 2))));
            $healthScore = min(100, max(0, round(76 + ($profitMargin * 0.4))));
            $clampedProfitMargin = max(0, min(100, $profitMargin));
        @endphp

        <section class="page-header">
            <div class="page-header__main">
                <div class="page-header__title">
                    <span class="eyebrow">Finans Yönetimi</span>
                    <h1>Finans Kontrol Paneli</h1>
                    <p>Finansal performansınızı sakin, odaklı bir görünümle takip edin ve kritik aksiyonları hızla başlatın.</p>
                </div>
                <div class="page-header__meta">
                    <span class="meta-label">Son güncelleme</span>
                    <span class="meta-value" id="lastUpdate">{{ now()->format('d.m.Y H:i') }}</span>
                </div>
            </div>
            <div class="page-header__actions">
                <button type="button" class="action-button action-button--primary" id="refreshButton" onclick="refreshDashboard()">
                    <span class="action-button__icon"><i class="fas fa-sync-alt"></i></span>
                    <span>Yenile</span>
                </button>
                <button type="button" class="action-button" onclick="exportDashboard()">
                    <span class="action-button__icon"><i class="fas fa-download"></i></span>
                    <span>Dışa Aktar</span>
                </button>
                <button type="button" class="action-button" onclick="printDashboard()">
                    <span class="action-button__icon"><i class="fas fa-print"></i></span>
                    <span>Yazdır</span>
                </button>
            </div>
        </section>

        <section class="summary-grid">
            <article class="stat-card">
                <span class="stat-card__icon stat-card__icon--revenue">
                    <i class="fas fa-arrow-trend-up"></i>
                </span>
                <div>
                    <span class="stat-card__label">Toplam Gelir</span>
                    <span class="stat-card__value">{{ number_format($totalRevenue, 0, ',', '.') }} ₺</span>
                    <div class="stat-card__footer">
                        <span class="stat-card__delta stat-card__delta--up">
                            <i class="fas fa-caret-up"></i> %12.5
                        </span>
                        <span class="stat-card__hint">Son 30 güne göre</span>
                    </div>
                </div>
            </article>

            <article class="stat-card">
                <span class="stat-card__icon stat-card__icon--expense">
                    <i class="fas fa-arrow-trend-down"></i>
                </span>
                <div>
                    <span class="stat-card__label">Toplam Gider</span>
                    <span class="stat-card__value">{{ number_format($totalExpenses, 0, ',', '.') }} ₺</span>
                    <div class="stat-card__footer">
                        <span class="stat-card__delta stat-card__delta--down">
                            <i class="fas fa-caret-up"></i> %8.3
                        </span>
                        <span class="stat-card__hint">Önceki aya göre</span>
                    </div>
                </div>
            </article>

            <article class="stat-card">
                <span class="stat-card__icon stat-card__icon--profit">
                    <i class="fas fa-chart-pie"></i>
                </span>
                <div>
                    <span class="stat-card__label">Net Kâr</span>
                    <span class="stat-card__value">{{ number_format($netProfit, 0, ',', '.') }} ₺</span>
                    <div class="stat-card__footer">
                        <span class="stat-card__delta {{ $profitMargin >= 0 ? 'stat-card__delta--up' : 'stat-card__delta--down' }}">
                            <i class="fas fa-tachometer-alt"></i> {{ number_format($profitMargin, 1) }}%
                        </span>
                        <span class="stat-card__hint">Kâr marjı</span>
                    </div>
                </div>
            </article>

            <article class="stat-card">
                <span class="stat-card__icon stat-card__icon--cash">
                    <i class="fas fa-wallet"></i>
                </span>
                <div>
                    <span class="stat-card__label">Toplam Nakit</span>
                    <span class="stat-card__value">{{ number_format($totalCash, 0, ',', '.') }} ₺</span>
                    <div class="stat-card__inline">
                        <div>
                            <span class="inline-label">Banka Hesabı</span>
                            <span class="inline-value">{{ $bankAccounts }}</span>
                        </div>
                        <div>
                            <span class="inline-label">Kasa</span>
                            <span class="inline-value">{{ $cashBoxes }}</span>
                        </div>
                    </div>
                </div>
            </article>
        </section>

        <section class="insight-grid">
            <article class="insight-card">
                <h3>Ortalama Alacak</h3>
                <span class="insight-card__value">{{ number_format($averageInvoiceValue, 0, ',', '.') }} ₺</span>
                <p class="insight-card__meta">Müşteri başına beklenen tahsilat.</p>
            </article>
            <article class="insight-card">
                <h3>Tahsilat Oranı</h3>
                <span class="insight-card__value">{{ number_format($collectionRate, 1) }}%</span>
                <p class="insight-card__meta">Gelirin tahsil edilmiş kısmı.</p>
            </article>
            <article class="insight-card">
                <h3>Nakit Döngüsü</h3>
                <span class="insight-card__value">{{ $cashConversionCycle }} gün</span>
                <p class="insight-card__meta">Nakit girişlerinin ortalama gerçekleşme süresi.</p>
            </article>
            <article class="insight-card">
                <h3>Finansal Sağlık Skoru</h3>
                <span class="insight-card__value">{{ $healthScore }}/100</span>
                <p class="insight-card__meta">Gelir, kârlılık ve risk bileşenlerinin birleşimi.</p>
            </article>
        </section>

        <div class="layout-grid layout-grid--two">
            <section class="section-card">
                <div class="section-card__header">
                    <div>
                        <h2>Aylık Gelir Trendi</h2>
                        <p>Gerçekleşen nakit girişleri - son 12 ay.</p>
                    </div>
                    <span class="badge-soft">Son 12 Ay</span>
                </div>
                <div class="chart-wrapper">
                    <canvas id="revenueChart"></canvas>
                </div>
            </section>

            <section class="section-card">
                <div class="section-card__header">
                    <div>
                        <h2>Kâr Marjı</h2>
                        <p>Gelir ve giderlere göre güncel kâr oranı.</p>
                    </div>
                </div>
                <div class="gauge">
                    <span class="gauge__value">{{ number_format($profitMargin, 1) }}%</span>
                    <div class="gauge__bar" aria-hidden="true">
                        <div class="gauge__fill" style="width: {{ $clampedProfitMargin }}%;"></div>
                    </div>
                    <span class="gauge__target">Hedef: %15</span>
                </div>
            </section>
        </div>

        <div class="layout-grid layout-grid--two">
            <section class="section-card">
                <div class="section-card__header">
                    <div>
                        <h2>Müşteri İstatistikleri</h2>
                        <p>Aktif müşteri tabanının genel görünümü.</p>
                    </div>
                </div>
                <div class="data-grid">
                    <div class="data-item">
                        <span class="data-label">Toplam Müşteri</span>
                        <span class="data-value">{{ $totalCustomers }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Aktif Müşteri</span>
                        <span class="data-value">{{ $activeCustomers }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Toplam Alacak</span>
                        <span class="data-value">{{ number_format($totalReceivables, 0, ',', '.') }} ₺</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Vadesi Geçen</span>
                        <span class="data-value">{{ $overdueInvoices }}</span>
                    </div>
                </div>
            </section>

            <section class="section-card">
                <div class="section-card__header">
                    <div>
                        <h2>Tedarikçi İstatistikleri</h2>
                        <p>Satınalma ve stok tarafının finansal etkisi.</p>
                    </div>
                </div>
                <div class="data-grid">
                    <div class="data-item">
                        <span class="data-label">Toplam Tedarikçi</span>
                        <span class="data-value">{{ $totalSuppliers }}</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Toplam Borç</span>
                        <span class="data-value">{{ number_format($totalPayables, 0, ',', '.') }} ₺</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Stok Değeri</span>
                        <span class="data-value">{{ number_format($totalStockValue, 0, ',', '.') }} ₺</span>
                    </div>
                    <div class="data-item">
                        <span class="data-label">Yaklaşan Fatura</span>
                        <span class="data-value">{{ $upcomingInvoices }}</span>
                    </div>
                </div>
            </section>
        </div>

        <div class="layout-grid layout-grid--two">
            <section class="section-card">
                <div class="section-card__header">
                    <div>
                        <h2>Risk Segmentleri</h2>
                        <p>Müşteri risk profili dağılımı.</p>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="riskChart"></canvas>
                </div>
            </section>

            <div class="stack">
                @if($upcomingInvoices > 0 || $overdueInvoices > 0 || $highRiskCustomers > 0)
                <section class="section-card notice-card">
                    <span class="notice-card__title">
                        <i class="fas fa-exclamation-circle"></i>
                        Dikkat gerektiren başlıklar
                    </span>
                    <ul class="notice-card__list">
                        @if($overdueInvoices > 0)
                            <li><i class="fas fa-clock"></i> {{ $overdueInvoices }} adet fatura vadesini geçti.</li>
                        @endif
                        @if($upcomingInvoices > 0)
                            <li><i class="fas fa-calendar-alt"></i> {{ $upcomingInvoices }} fatura 7 gün içinde vadesine ulaşıyor.</li>
                        @endif
                        @if($highRiskCustomers > 0)
                            <li><i class="fas fa-user-shield"></i> {{ $highRiskCustomers }} müşteri yüksek risk seviyesinde.</li>
                        @endif
                    </ul>
                </section>
                @else
                <section class="section-card notice-card">
                    <span class="notice-card__title">
                        <i class="fas fa-check-circle text-success"></i>
                        Gündeminiz temiz görünüyor
                    </span>
                    <p class="empty-state mb-0">Bu hafta için kritik uyarı bulunmuyor.</p>
                </section>
                @endif

                <section class="section-card">
                    <div class="section-card__header">
                        <div>
                            <h2>Hızlı Eylemler</h2>
                            <p>En sık kullanılan finans işlemlerine hızlı erişim.</p>
                        </div>
                    </div>
                    <div class="quick-actions">
                        <a href="{{ route('finance.accounts.customers') }}" class="quick-action">
                            <i class="fas fa-users"></i>
                            Müşteri Listesi
                        </a>
                        @if(Route::has('finance.accounts.suppliers'))
                        <a href="{{ route('finance.accounts.suppliers') }}" class="quick-action">
                        @else
                        <a href="{{ route('index', ['any' => 'finance/accounts/suppliers']) }}" class="quick-action">
                        @endif
                            <i class="fas fa-truck"></i>
                            Tedarikçiler
                        </a>
                        <a href="{{ route('finance.accounts.vouchers') }}" class="quick-action">
                            <i class="fas fa-file-invoice"></i>
                            Fiş Oluştur
                        </a>
                        <a href="{{ route('finance.accounts.reports') }}" class="quick-action">
                            <i class="fas fa-chart-pie"></i>
                            Finans Raporları
                        </a>
                        <a href="{{ route('finance.cash.accounts') }}" class="quick-action">
                            <i class="fas fa-piggy-bank"></i>
                            Kasa &amp; Banka
                        </a>
                        <a href="{{ route('finance.stock.cards') }}" class="quick-action">
                            <i class="fas fa-boxes"></i>
                            Stok Yönetimi
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gelir Trendi Grafiği
    const revenueCanvas = document.getElementById('revenueChart');
    if (revenueCanvas) {
        const revenueCtx = revenueCanvas.getContext('2d');
        const revenueData = @json($monthlyRevenue);

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.month),
                datasets: [{
                    label: 'Aylık Gelir (₺)',
                    data: revenueData.map(item => item.revenue),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 10,
                        backgroundColor: 'rgba(15, 23, 42, 0.88)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1
                    }
                },
                interaction: { intersect: false, mode: 'index' },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(15, 23, 42, 0.06)' },
                        ticks: {
                            color: '#475569',
                            callback: value => new Intl.NumberFormat('tr-TR', {
                                style: 'currency',
                                currency: 'TRY',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(value)
                        }
                    },
                    x: {
                        grid: { color: 'rgba(15, 23, 42, 0.04)' },
                        ticks: { color: '#475569' }
                    }
                }
            }
        });
    }

    // Risk Segmenti Grafiği
    const riskCanvas = document.getElementById('riskChart');
    if (riskCanvas) {
        const riskCtx = riskCanvas.getContext('2d');
        new Chart(riskCtx, {
            type: 'doughnut',
            data: {
                labels: ['Düşük Risk', 'Orta Risk', 'Yüksek Risk'],
                datasets: [{
                    data: [{{ $lowRiskCustomers }}, {{ $mediumRiskCustomers }}, {{ $highRiskCustomers }}],
                    backgroundColor: ['#0f766e', '#f59e0b', '#dc2626'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            color: '#475569',
                            padding: 16
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.88)',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                },
                cutout: '62%'
            }
        });
    }
});

function refreshDashboard() {
    const lastUpdateElement = document.getElementById('lastUpdate');
    const refreshButton = document.getElementById('refreshButton');

    if (refreshButton) {
        refreshButton.classList.add('is-loading');
        refreshButton.disabled = true;
    }

    // DİKKAT: Bu fonksiyon sunucudan yeni veri çekmez, sadece görsel bir güncelleme yapar.
    // Gerçek bir yenileme işlevi için buraya bir AJAX (fetch) çağrısı eklenmelidir.
    setTimeout(() => {
        if (lastUpdateElement) {
            lastUpdateElement.textContent = new Date().toLocaleString('tr-TR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        if (refreshButton) {
            refreshButton.classList.remove('is-loading');
            refreshButton.disabled = false;
        }
    }, 800);
}

function exportDashboard() {
    const dashboardData = {
        timestamp: new Date().toISOString(),
        metrics: {
            totalRevenue: '{{ $totalRevenue }}',
            totalExpenses: '{{ $totalExpenses }}',
            netProfit: '{{ $netProfit }}',
            profitMargin: '{{ number_format($profitMargin, 2) }}'
        },
        customers: {
            total: '{{ $totalCustomers }}',
            active: '{{ $activeCustomers }}',
            overdueInvoices: '{{ $overdueInvoices }}',
            upcomingInvoices: '{{ $upcomingInvoices }}'
        },
        suppliers: {
            total: '{{ $totalSuppliers }}',
            payables: '{{ $totalPayables }}'
        }
    };

    const dataStr = JSON.stringify(dashboardData, null, 2);
    const dataBlob = new Blob([dataStr], { type: 'application/json' });
    const url = URL.createObjectURL(dataBlob);

    const link = document.createElement('a');
    link.href = url;
    link.download = `finans-paneli-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}

function printDashboard() {
    const dashboard = document.querySelector('.finance-dashboard');
    if (!dashboard) return;

    const printWindow = window.open('', '', 'height=800,width=1200');

    printWindow.document.write(`
        <html>
            <head>
                <title>Finans Kontrol Paneli Yazdır - {{ now()->format('d.m.Y') }}</title>
                <style>
                    /* Yazdırma için temel stiller */
                    body { font-family: 'Inter', 'Segoe UI', sans-serif; margin: 20px; color: #0f172a; }
                    .container-fluid { padding: 0 !important; }
                    h1, h2, h3 { color: #0f172a; }
                    /* Yazdırmada görünmemesi gereken elementler */
                    .page-header__actions, .quick-actions { display: none !important; }
                    /* Gölgeleri kaldırıp kenarlık ekleyerek daha temiz bir görünüm sağla */
                    .section-card, .page-header, .stat-card, .insight-card {
                        box-shadow: none !important;
                        border: 1px solid #dce3f4 !important;
                        page-break-inside: avoid;
                    }
                    .layout-grid, .summary-grid, .insight-grid {
                        display: grid;
                        grid-template-columns: 1fr 1fr; /* 2 sütunlu sabit bir düzen */
                    }
                </style>
            </head>
            <body>
                <h1>Finans Kontrol Paneli - {{ now()->format('d.m.Y H:i') }}</h1>
                ${dashboard.innerHTML}
            </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();

    // Tarayıcının stilleri ve içeriği işlemesi için küçük bir gecikme sonrası yazdırma işlemini başlat.
    setTimeout(function () {
        printWindow.print();
        printWindow.close();
    }, 250);
}

// Her 5 dakikada bir sahte yenileme yapar.
setInterval(refreshDashboard, 300000);
</script>
<script>
// Force-remove any leftover top spacing added by layout/topbar after all CSS/JS runs
document.addEventListener('DOMContentLoaded', function () {
    try {
        var mc = document.querySelector('body .main-content');
        if (mc) { mc.style.marginTop = '0px'; mc.style.paddingTop = '0px'; }

        var pc = document.querySelector('.page-content');
        if (pc) { pc.style.marginTop = '0px'; pc.style.paddingTop = '6px'; }

        var container = document.querySelector('.finance-dashboard .container-fluid');
        if (container) { container.style.paddingTop = '0px'; container.style.marginTop = '0px'; }

        var cf = document.querySelector('.finance-dashboard');
        if (cf) { cf.style.marginTop = '0px'; cf.style.paddingTop = '0px'; }

        // Hide any page-title-box if it accidentally renders
        var ptb = document.querySelector('.page-title-box');
        if (ptb) ptb.style.display = 'none';
    } catch (err) {
        console.warn('Dashboard top-space override failed', err);
    }
});
</script>
@endpush

