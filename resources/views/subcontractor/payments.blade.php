@extends('layouts.master')

@section('title', 'Taşeron Ödemeleri')

@section('topbar-title', 'Taşeron Yönetimi')

@section('css')
@endsection

@section('content')
<div class="container-fluid">

    <!-- KPI Kartları -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-primary rounded">
                                <i class="mdi mdi-cash-multiple font-size-24"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Bu Ay Ödenen Tutar</p>
                            <h4 class="mb-0">{{ number_format($thisMonthPaid, 2) }} TL</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-warning rounded">
                                <i class="mdi mdi-clock-outline font-size-24"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Ödeme Bekleyen Tutar</p>
                            <h4 class="mb-0">{{ number_format($pendingAmount, 2) }} TL</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-success rounded">
                                <i class="mdi mdi-office-building font-size-24"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Aktif Taşeron Sayısı</p>
                            <h4 class="mb-0">{{ $activeSubcontractors }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light text-info rounded">
                                <i class="mdi mdi-chart-line font-size-24"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-muted mb-1">Toplam İşlem</p>
                            <h4 class="mb-0">{{ $payments->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafikler -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Aylık Ödeme Trendi</h4>
                </div>
                <div class="card-body">
                    <div id="monthly-payments-chart" data-colors='["--bs-primary"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Taşerona Göre Ödeme Dağılımı</h4>
                </div>
                <div class="card-body">
                    <div id="subcontractor-payments-chart" data-colors='["--bs-primary", "--bs-success", "--bs-warning", "--bs-danger", "--bs-info"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ödeme Kayıtları -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Ödeme Kayıtları</h4>
                </div>
                <div class="card-body">
                    <!-- Filtreleme Formu -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Başlangıç Tarihi</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Bitiş Tarihi</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="project" class="form-label">Proje</label>
                            <input type="text" class="form-control" id="project" name="project" placeholder="Proje adı" value="{{ request('project') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="subcontractor" class="form-label">Taşeron</label>
                            <input type="text" class="form-control" id="subcontractor" name="subcontractor" placeholder="Taşeron adı" value="{{ request('subcontractor') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Filtrele</button>
                            <a href="{{ route('subcontractor.payments') }}" class="btn btn-secondary">Temizle</a>
                        </div>
                    </form>

                    <!-- Ödeme Tablosu -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Ödeme Tarihi</th>
                                    <th>Taşeron Firma</th>
                                    <th>Proje</th>
                                    <th>İlişkili Hakediş No</th>
                                    <th>Ödenen Tutar</th>
                                    <th>Ödeme Yapan</th>
                                    <th>Dekont</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date ? $payment->payment_date->format('d.m.Y') : '-' }}</td>
                                    <td>{{ $payment->payee }}</td>
                                    <td>{{ $payment->project_name }}</td>
                                    <td>
                                        @if($payment->reference_type === 'progress_claim')
                                            HK-{{ str_pad($payment->reference_id, 3, '0', STR_PAD_LEFT) }}
                                        @else
                                            {{ $payment->reference_id }}
                                        @endif
                                    </td>
                                    <td>{{ number_format($payment->amount, 2) }} TL</td>
                                    <td>{{ $payment->processor ? $payment->processor->name : '-' }}</td>
                                    <td>
                                        @if($payment->receipt_path)
                                            <a href="{{ asset('storage/' . $payment->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">📄 Görüntüle</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('subcontractor.payments.show', $payment->id) }}" class="btn btn-sm btn-outline-info">Detay</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Ödeme kaydı bulunamadı.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Sayfalama -->
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Aylık Ödeme Trendi Grafiği
    var monthlyOptions = {
        series: [{
            name: 'Ödeme Tutarı (TL)',
            data: [
                @foreach($monthlyPayments as $month)
                    {{ $month->total }},
                @endforeach
            ]
        }],
        chart: {
            type: 'line',
            height: 300
        },
        xaxis: {
            categories: [
                @foreach($monthlyPayments as $month)
                    '{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('M Y') }}',
                @endforeach
            ]
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return value.toLocaleString('tr-TR') + ' TL';
                }
            }
        }
    };
    var monthlyChart = new ApexCharts(document.querySelector("#monthly-payments-chart"), monthlyOptions);
    monthlyChart.render();

    // Taşerona Göre Ödeme Dağılımı Grafiği
    var subcontractorOptions = {
        series: [{
            data: [
                @foreach($topSubcontractors as $sub)
                    {{ $sub->total }},
                @endforeach
            ]
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        plotOptions: {
            bar: {
                horizontal: true
            }
        },
        xaxis: {
            categories: [
                @foreach($topSubcontractors as $sub)
                    '{{ $sub->payee }}',
                @endforeach
            ]
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return value.toLocaleString('tr-TR') + ' TL';
                }
            }
        }
    };
    var subcontractorChart = new ApexCharts(document.querySelector("#subcontractor-payments-chart"), subcontractorOptions);
    subcontractorChart.render();
</script>
@endsection
