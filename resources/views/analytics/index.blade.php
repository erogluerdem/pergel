@extends('layouts.master')

@section('title','Analitik & Raporlar')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>📊 Analitik & Raporlar</h4>
            <p class="text-muted">Platformunuzda biriken tüm verilerin anlam kazandığı stratejik merkez</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('analytics.wizard') }}" class="btn btn-primary">
                <i class="mdi mdi-wizard-hat"></i> Rapor Sihirbazı
            </a>
        </div>
    </div>

    <!-- Ana Menü Kartları -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🔮 Öngörüsel Analitik (Predictive Analytics & BI)</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Geçmiş verilerden öğrenerek geleceği tahmin eden akıllı paneller</p>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('analytics.risk-map') }}" class="btn btn-outline-primary btn-sm w-100">
                                📍 Risk Haritası
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('analytics.benchmarking') }}" class="btn btn-outline-primary btn-sm w-100">
                                🏆 Performans Kıyaslaması
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('analytics.profit-forecast') }}" class="btn btn-outline-primary btn-sm w-100">
                                💰 Kârlılık Tahmini
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('analytics.what-if') }}" class="btn btn-outline-primary btn-sm w-100">
                                ❓ What-If Analizi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">🧙‍♂️ Kişiselleştirilebilir Rapor Sihirbazı</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Her kullanıcının kendi ihtiyacına yönelik raporlar oluşturabildiği esnek araç</p>
                    <div class="mb-3">
                        <strong>Özellikler:</strong>
                        <ul class="mb-0 small">
                            <li>📊 Sürükle-bırak rapor oluşturma</li>
                            <li>🔍 Gelişmiş filtreleme</li>
                            <li>📈 Çoklu görselleştirme seçenekleri</li>
                            <li>⏰ Otomatik rapor zamanlama</li>
                        </ul>
                    </div>
                    <a href="{{ route('analytics.wizard') }}" class="btn btn-success w-100">
                        <i class="mdi mdi-magic"></i> Sihirbazı Başlat
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hızlı İstatistikler -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <div class="text-primary mb-2">
                    <i class="mdi mdi-chart-line-variant mdi-24px"></i>
                </div>
                <h6>Aktif Projeler</h6>
                <h4>{{ $activeProjectsCount ?? 0 }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <div class="text-success mb-2">
                    <i class="mdi mdi-cash-multiple mdi-24px"></i>
                </div>
                <h6>Toplam Sözleşme Değeri</h6>
                <h4>{{ number_format($totalContractValue ?? 0, 0) }} ₺</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <div class="text-warning mb-2">
                    <i class="mdi mdi-alert-circle mdi-24px"></i>
                </div>
                <h6>Açık İSG Olayları</h6>
                <h4>{{ $openIncidentsCount ?? 0 }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <div class="text-info mb-2">
                    <i class="mdi mdi-account-group mdi-24px"></i>
                </div>
                <h6>Aktif Taşeronlar</h6>
                <h4>{{ $activeVendorsCount ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <!-- Son Raporlar -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">📋 Son Oluşturulan Raporlar</h5>
        </div>
        <div class="card-body">
            <div class="text-center text-muted py-4">
                <i class="mdi mdi-file-chart mdi-48px mb-3"></i>
                <p>Henüz rapor oluşturulmamış</p>
                <a href="{{ route('analytics.wizard') }}" class="btn btn-outline-primary">
                    İlk Raporunuzu Oluşturun
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Sayfa yüklendiğinde animasyonlar
document.addEventListener('DOMContentLoaded', function() {
    // Kartları sırayla göster
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection
