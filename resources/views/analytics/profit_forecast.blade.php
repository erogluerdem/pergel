@extends('layouts.master')

@section('title','Proje Kârlılık Tahmini')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>💰 Proje Kârlılık Tahmini</h4>
            <p class="text-muted">Mevcut maliyet trendlerine göre projelerin tahmini kârlılık analizi</p>
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

    <!-- Kârlılık Özeti -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <div class="text-success mb-2">
                    <i class="mdi mdi-trending-up mdi-24px"></i>
                </div>
                <h6>Kârlı Projeler</h6>
                <h4>{{ $projects->where('profit', '>', 0)->count() }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <div class="text-danger mb-2">
                    <i class="mdi mdi-trending-down mdi-24px"></i>
                </div>
                <h6>Zararlı Projeler</h6>
                <h4>{{ $projects->where('profit', '<', 0)->count() }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <div class="text-primary mb-2">
                    <i class="mdi mdi-cash mdi-24px"></i>
                </div>
                <h6>Toplam Tahmini Kâr</h6>
                <h4>{{ number_format($projects->sum('profit'), 0) }} ₺</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <div class="text-warning mb-2">
                    <i class="mdi mdi-percent mdi-24px"></i>
                </div>
                <h6>Ortalama Kâr Marjı</h6>
                <h4>{{ number_format($projects->avg('profit_margin'), 1) }}%</h4>
            </div>
        </div>
    </div>

    <!-- Kârlılık Grafiği -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Proje Bazlı Kârlılık Tahmini</h5>
        </div>
        <div class="card-body">
            <canvas id="profitChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Detaylı Tablo -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Proje Kârlılık Detayları</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Proje Adı</th>
                            <th>Sözleşme Değeri</th>
                            <th>Mevcut Maliyet</th>
                            <th>Tahmini Toplam Maliyet</th>
                            <th>Tahmini Kâr/Zarar</th>
                            <th>Kâr Marjı</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                        <tr>
                            <td><strong>{{ $project['name'] }}</strong></td>
                            <td>{{ number_format($project['budget'], 0) }} ₺</td>
                            <td>{{ number_format($project['current_cost'], 0) }} ₺</td>
                            <td>{{ number_format($project['projected_cost'], 0) }} ₺</td>
                            <td class="{{ $project['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                <strong>{{ number_format($project['profit'], 0) }} ₺</strong>
                            </td>
                            <td>
                                <span class="badge {{ $project['profit_margin'] >= 10 ? 'bg-success' : ($project['profit_margin'] >= 0 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $project['profit_margin'] }}%
                                </span>
                            </td>
                            <td>
                                @if($project['profit'] > 0)
                                    <span class="badge bg-success">Kârlı</span>
                                @elseif($project['profit'] == 0)
                                    <span class="badge bg-warning">Break-even</span>
                                @else
                                    <span class="badge bg-danger">Zararlı</span>
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

    <!-- Uyarı Bölümü -->
    <div class="alert alert-warning mt-4" role="alert">
        <h6 class="alert-heading">⚠️ Tahmin Metodolojisi</h6>
        <p class="mb-0">Bu kârlılık tahminleri, projenin şu anki ilerleme oranı ve maliyet trendlerine dayanmaktadır. Tahminler, gelecekteki piyasa koşulları, ek maliyetler veya değişiklikler göz önünde bulundurulmadan hesaplanmıştır.</p>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('profitChart').getContext('2d');

    const projects = @json($projects);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: projects.map(p => p.name.length > 20 ? p.name.substring(0, 20) + '...' : p.name),
            datasets: [{
                label: 'Tahmini Kâr/Zarar (₺)',
                data: projects.map(p => p.profit),
                backgroundColor: projects.map(p =>
                    p.profit >= 0 ? 'rgba(25, 135, 84, 0.8)' : 'rgba(220, 53, 69, 0.8)'
                ),
                borderColor: projects.map(p =>
                    p.profit >= 0 ? 'rgba(25, 135, 84, 1)' : 'rgba(220, 53, 69, 1)'
                ),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const project = projects[context.dataIndex];
                            return [
                                `Kâr/Zarar: ${new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(project.profit)}`,
                                `Marj: ${project.profit_margin}%`,
                                `Bütçe: ${new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(project.budget)}`
                            ];
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Tahmini Kâr/Zarar (₺)'
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', notation: 'compact' }).format(value);
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Projeler'
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
