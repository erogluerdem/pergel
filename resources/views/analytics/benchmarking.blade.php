@extends('layouts.master')

@section('title','Taşeron Performans Kıyaslaması')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>🏆 Taşeron Performans Kıyaslaması</h4>
            <p class="text-muted">Taşeron ve tedarikçilerin çoklu kriterlerde karşılaştırmalı analizi</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('analytics.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left"></i> Geri
            </a>
            <button class="btn btn-outline-primary" onclick="refreshData()">
                <i class="mdi mdi-refresh"></i> Yenile
            </button>
        </div>
    </div>

    <!-- Kıyaslama Kriterleri Açıklaması -->
    <div class="card mb-4">
        <div class="card-body">
            <h6>Değerlendirme Kriterleri</h6>
            <div class="row">
                <div class="col-md-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="mdi mdi-star text-warning me-2"></i>
                        <strong>İş Kalitesi:</strong>
                    </div>
                    <p class="small text-muted">Teslim edilen işin standartlara uygunluğu</p>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="mdi mdi-clock-outline text-info me-2"></i>
                        <strong>Zaman Planına Uyum:</strong>
                    </div>
                    <p class="small text-muted">Teslim tarihlerine riayet oranı</p>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="mdi mdi-shield-check text-success me-2"></i>
                        <strong>İSG Performansı:</strong>
                    </div>
                    <p class="small text-muted">İş güvenliği standartlarına uygunluk</p>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="mdi mdi-cash text-primary me-2"></i>
                        <strong>Maliyet Kontrolü:</strong>
                    </div>
                    <p class="small text-muted">Bütçe aşımı ve maliyet etkinliği</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Performans Grafikleri -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card" style="min-height: 500px;">
                <div class="card-header">
                    <h5 class="mb-0">Ortalama Performans Skorları</h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" width="600" height="400"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" style="min-height: 500px;">
                <div class="card-header">
                    <h5 class="mb-0">Proje Tamamlanma Oranları</h5>
                </div>
                <div class="card-body">
                    <canvas id="completionChart" width="600" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detaylı Karne Tablosu -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Taşeron Performans Karnesi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Taşeron</th>
                            <th>İş Kalitesi</th>
                            <th>Zaman Uyumu</th>
                            <th>İSG</th>
                            <th>Maliyet Kontrolü</th>
                            <th>Genel Ortalama</th>
                            <th>Proje Sayısı</th>
                            <th>Tamamlanma Oranı</th>
                            <th>Değerlendirme</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendors as $vendor)
                        <tr>
                            <td>
                                <strong>{{ $vendor['name'] }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar {{ $vendor['quality'] >= 4 ? 'bg-success' : ($vendor['quality'] >= 3 ? 'bg-warning' : 'bg-danger') }}"
                                             style="width: {{ ($vendor['quality'] / 5) * 100 }}%"></div>
                                    </div>
                                    <span class="small">{{ $vendor['quality'] }}/5</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar {{ $vendor['timeliness'] >= 4 ? 'bg-success' : ($vendor['timeliness'] >= 3 ? 'bg-warning' : 'bg-danger') }}"
                                             style="width: {{ ($vendor['timeliness'] / 5) * 100 }}%"></div>
                                    </div>
                                    <span class="small">{{ $vendor['timeliness'] }}/5</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar {{ $vendor['safety'] >= 4 ? 'bg-success' : ($vendor['safety'] >= 3 ? 'bg-warning' : 'bg-danger') }}"
                                             style="width: {{ ($vendor['safety'] / 5) * 100 }}%"></div>
                                    </div>
                                    <span class="small">{{ $vendor['safety'] }}/5</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar {{ $vendor['cost_control'] >= 4 ? 'bg-success' : ($vendor['cost_control'] >= 3 ? 'bg-warning' : 'bg-danger') }}"
                                             style="width: {{ ($vendor['cost_control'] / 5) * 100 }}%"></div>
                                    </div>
                                    <span class="small">{{ $vendor['cost_control'] }}/5</span>
                                </div>
                            </td>
                            <td>
                                <strong>{{ number_format(($vendor['quality'] + $vendor['timeliness'] + $vendor['safety'] + $vendor['cost_control']) / 4, 1) }}/5</strong>
                            </td>
                            <td>{{ $vendor['total_projects'] }}</td>
                            <td>
                                <span class="badge {{ $vendor['completion_rate'] >= 90 ? 'bg-success' : ($vendor['completion_rate'] >= 70 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $vendor['completion_rate'] }}%
                                </span>
                            </td>
                            <td>
                                @php
                                    $avg = ($vendor['quality'] + $vendor['timeliness'] + $vendor['safety'] + $vendor['cost_control']) / 4;
                                @endphp
                                @if($avg >= 4)
                                    <span class="badge bg-success">Mükemmel</span>
                                @elseif($avg >= 3)
                                    <span class="badge bg-primary">İyi</span>
                                @elseif($avg >= 2)
                                    <span class="badge bg-warning">Ortalama</span>
                                @else
                                    <span class="badge bg-danger">Geliştirilmeli</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const vendors = @json($vendors);

    console.log('Vendors:', vendors);
    vendors.forEach(v => console.log(`${v.name}: q=${v.quality}, t=${v.timeliness}, s=${v.safety}, c=${v.cost_control}`));

    const datasets = vendors.slice(0, 5).map((vendor, index) => ({
        label: vendor.name,
        data: [vendor.quality, vendor.timeliness, vendor.safety, vendor.cost_control],
        borderColor: `hsl(${(index * 60) % 360}, 70%, 50%)`,
        backgroundColor: `hsla(${(index * 60) % 360}, 70%, 50%, 0.1)`,
        borderWidth: 2,
        pointRadius: 4
    }));

    console.log('Datasets:', datasets);

    // Performans grafiği
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    try {
        new Chart(performanceCtx, {
            type: 'radar',
            data: {
                labels: ['İş Kalitesi', 'Zaman Uyumu', 'İSG', 'Maliyet Kontrolü'],
                datasets: vendors.slice(0, 5).map((vendor, index) => ({
                    label: vendor.name,
                    data: [vendor.quality, vendor.timeliness, vendor.safety, vendor.cost_control],
                    borderColor: `hsl(${(index * 60) % 360}, 70%, 50%)`,
                    backgroundColor: `hsla(${(index * 60) % 360}, 70%, 50%, 0.1)`,
                    borderWidth: 2,
                    pointRadius: 4
                }))
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.r;
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 5
                    }
                }
            }
        });
    } catch (e) {
        console.error('Benchmarking chart error:', e);
    }    // Tamamlanma oranı grafiği
    const completionCtx = document.getElementById('completionChart').getContext('2d');
    new Chart(completionCtx, {
        type: 'bar',
        data: {
            labels: vendors.map(v => v.name),
            datasets: [{
                label: 'Proje Tamamlanma Oranı (%)',
                data: vendors.map(v => v.completion_rate),
                backgroundColor: vendors.map((v, i) =>
                    v.completion_rate >= 90 ? 'rgba(25, 135, 84, 0.8)' :
                    v.completion_rate >= 70 ? 'rgba(255, 193, 7, 0.8)' :
                    'rgba(220, 53, 69, 0.8)'
                ),
                borderColor: vendors.map((v, i) =>
                    v.completion_rate >= 90 ? 'rgba(25, 135, 84, 1)' :
                    v.completion_rate >= 70 ? 'rgba(255, 193, 7, 1)' :
                    'rgba(220, 53, 69, 1)'
                ),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Tamamlanma Oranı (%)'
                    }
                }
            }
        }
    });
});

function refreshData() {
    location.reload();
}
</script>
@endsection
