@extends('layouts.master')

@section('title','Portföy Risk Haritası')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>📍 Portföy Risk Haritası</h4>
            <p class="text-muted">Maliyet ve zaman sapmalarına göre projelerin risk dağılımı</p>
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

    <!-- Risk Haritası Açıklaması -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h6>Nasıl Okunur?</h6>
                    <p class="mb-2">Grafik, tüm aktif projeleri <strong>Maliyet Sapması (%)</strong> ve <strong>Zaman Sapması (%)</strong> eksenlerinde konumlandırır.</p>
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-danger" style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;"></div>
                                <span class="small">Tehlikeli Bölge (Sağ Üst)</span>
                            </div>
                            <p class="small text-muted">Hem bütçe aşımı hem de gecikme olan projeler</p>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-warning" style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;"></div>
                                <span class="small">Dikkat Bölgesi</span>
                            </div>
                            <p class="small text-muted">Ya bütçe ya da zaman sorunu olan projeler</p>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-success" style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;"></div>
                                <span class="small">Güvenli Bölge (Sol Alt)</span>
                            </div>
                            <p class="small text-muted">Bütçe ve zamanında ilerleyen projeler</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h6>Risk Özeti</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tehlikeli Projeler:</span>
                        <span class="badge bg-danger">{{ $projects->where('cost_deviation', '>', 10)->where('time_deviation', '>', 10)->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Dikkat Gerektiren:</span>
                        <span class="badge bg-warning">{{ $projects->filter(function($p) { return ($p['cost_deviation'] ?? 0) > 10 || ($p['time_deviation'] ?? 0) > 10; })->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Güvenli Projeler:</span>
                        <span class="badge bg-success">{{ $projects->where('cost_deviation', '<=', 10)->where('time_deviation', '<=', 10)->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Risk Haritası Grafiği -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Proje Risk Dağılımı</h5>
        </div>
        <div class="card-body">
            <canvas id="riskMapChart" width="400" height="300"></canvas>
        </div>
    </div>

    <!-- Proje Detay Tablosu -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Proje Detayları</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Proje Adı</th>
                            <th>Maliyet Sapması (%)</th>
                            <th>Zaman Sapması (%)</th>
                            <th>Risk Seviyesi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                        <tr>
                            <td>{{ $project['name'] }}</td>
                            <td class="{{ $project['cost_deviation'] > 10 ? 'text-danger' : ($project['cost_deviation'] > 0 ? 'text-warning' : 'text-success') }}">
                                {{ $project['cost_deviation'] }}%
                            </td>
                            <td class="{{ $project['time_deviation'] > 10 ? 'text-danger' : ($project['time_deviation'] > 0 ? 'text-warning' : 'text-success') }}">
                                {{ $project['time_deviation'] }}%
                            </td>
                            <td>
                                @if($project['cost_deviation'] > 10 && $project['time_deviation'] > 10)
                                    <span class="badge bg-danger">Yüksek Risk</span>
                                @elseif($project['cost_deviation'] > 10 || $project['time_deviation'] > 10)
                                    <span class="badge bg-warning">Orta Risk</span>
                                @else
                                    <span class="badge bg-success">Düşük Risk</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('portfolio.project.show', ['id' => \App\Models\Project::where('name', $project['name'])->first()?->id ?? 0]) }}" class="btn btn-sm btn-outline-primary">
                                    Detaylar
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Use reliable CDN for Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('riskMapChart').getContext('2d');

    const projects = @json($projects);

    console.log('Projects:', projects);

    projects.forEach(p => console.log(`${p.name}: cost_dev=${p.cost_deviation}, time_dev=${p.time_deviation}`));

    const datasets = [];
    const colors = {
        high: 'rgba(220, 53, 69, 0.8)',    // danger
        medium: 'rgba(255, 193, 7, 0.8)',  // warning
        low: 'rgba(25, 135, 84, 0.8)'      // success
    };

    // Verileri risk seviyesine göre grupla
    const highRisk = projects.filter(p => p.cost_deviation > 10 && p.time_deviation > 10);
    const mediumRisk = projects.filter(p => (p.cost_deviation > 10 || p.time_deviation > 10) && !(p.cost_deviation > 10 && p.time_deviation > 10));
    const lowRisk = projects.filter(p => p.cost_deviation <= 10 && p.time_deviation <= 10);

    console.log('High risk:', highRisk);
    console.log('Medium risk:', mediumRisk);
    console.log('Low risk:', lowRisk);

    if (highRisk.length > 0) {
        datasets.push({
            label: 'Yüksek Risk',
            data: highRisk.map(p => ({ x: p.cost_deviation, y: p.time_deviation })),
            backgroundColor: colors.high,
            borderColor: colors.high.replace('0.8', '1'),
            borderWidth: 2,
            pointRadius: 8
        });
    }

    if (mediumRisk.length > 0) {
        datasets.push({
            label: 'Orta Risk',
            data: mediumRisk.map(p => ({ x: p.cost_deviation, y: p.time_deviation })),
            backgroundColor: colors.medium,
            borderColor: colors.medium.replace('0.8', '1'),
            borderWidth: 2,
            pointRadius: 6
        });
    }

    if (lowRisk.length > 0) {
        datasets.push({
            label: 'Düşük Risk',
            data: lowRisk.map(p => ({ x: p.cost_deviation, y: p.time_deviation })),
            backgroundColor: colors.low,
            borderColor: colors.low.replace('0.8', '1'),
            borderWidth: 2,
            pointRadius: 5
        });
    }

    console.log('Datasets:', datasets);

    try {
        new Chart(ctx, {
            type: 'scatter',
            data: {
                datasets: datasets
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
                                const project = projects.find(p =>
                                    p.cost_deviation === context.parsed.x &&
                                    p.time_deviation === context.parsed.y
                                );
                                return project ? project.name : '';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Maliyet Sapması (%)'
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Zaman Sapması (%)'
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });
    } catch (e) {
        console.error('Chart error:', e);
    }
});

function refreshData() {
    location.reload();
}
</script>
@endsection
