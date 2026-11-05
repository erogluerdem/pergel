@extends('layouts.master')

@section('title', 'Proje Maliyet Analizi - ' . $project->name)

@section('topbar-title', 'Projeler')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $project->name }} - Maliyet Analizi</h3>
            </div>
                <div class="card-body">
                    <!-- Toplam Harcama ve Bütçe Kullanım -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Toplam Harcama</span>
                                    <span class="info-box-number">{{ number_format($totalExpenses, 2) }} TL</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-chart-pie"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Bütçe Kullanım Oranı</span>
                                    <span class="info-box-number">{{ number_format($budgetUsage, 2) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pasta Grafik -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Harcama Kategorileri Dağılımı</h5>
                                </div>
                                <div class="card-body" style="max-height: 300px;">
                                    <canvas id="expenseCategoriesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Son Harcamalar Tablosu -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Son Harcamalar</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tarih</th>
                                                <th>Kategori</th>
                                                <th>Tutar</th>
                                                <th>Açıklama</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentExpenses as $expense)
                                            <tr>
                                                <td>{{ $expense->expense_date->format('d.m.Y') }}</td>
                                                <td>{{ $expense->category->name ?? 'Bilinmeyen' }}</td>
                                                <td>{{ number_format($expense->amount, 2) }} TL</td>
                                                <td>{{ $expense->description }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Villa Maliyet Analizi -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Villa Maliyet Analizi</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Villa Adı</th>
                                                <th>Hedef Maliyet</th>
                                                <th>Gerçekleşen Maliyet</th>
                                                <th>Fark (TL)</th>
                                                <th>Fark (%)</th>
                                                <th>Durum</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($villas as $villa)
                                            <tr>
                                                <td>{{ $villa->name }}</td>
                                                <td>{{ number_format($villa->target_cost, 2) }} TL</td>
                                                <td>{{ number_format($villa->actual_cost, 2) }} TL</td>
                                                <td class="{{ $villa->variance >= 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($villa->variance, 2) }} TL
                                                </td>
                                                <td class="{{ $villa->variance_percentage >= 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($villa->variance_percentage, 2) }}%
                                                </td>
                                                <td>
                                                    @if($villa->variance <= 0)
                                                        <span class="badge bg-success">Hedef altında</span>
                                                    @elseif($villa->variance_percentage < 10)
                                                        <span class="badge bg-warning">Kabul edilebilir</span>
                                                    @else
                                                        <span class="badge bg-danger">Hedef üstü</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('projects.villas.edit', [$project, $villa]) }}" class="btn btn-sm btn-primary">
                                                        <i class="bx bx-edit"></i> Düzenle
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
                </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pasta Grafik Verileri
    const categoryData = @json($expensesByCategory);

    const labels = categoryData.map(item => item.category);
    const data = categoryData.map(item => item.total);

    const ctx = document.getElementById('expenseCategoriesChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
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
                            return context.label + ': ' + context.parsed.toFixed(2) + ' TL';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
