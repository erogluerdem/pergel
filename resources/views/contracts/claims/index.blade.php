@extends('layouts.master')

@section('title','Hak Talepleri Yönetimi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <h3>Hak Talepleri Yönetimi</h3>
        <div>
            <a href="{{ route('contracts.claims.create') }}" class="btn btn-primary">➕ Yeni Hak Talebi Oluştur</a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Toplam Açık Talepler</h6>
                    <h3>{{ number_format($totalOpen) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Potansiyel Maliyet Riski</h6>
                    <h3>{{ number_format($totalPotentialCost,2,',','.') }} TL</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Potansiyel Süre Uzatım Riski</h6>
                    <h3>{{ number_format($totalPotentialDays) }} Gün</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Cevap Bekleyen Talepler</h6>
                    <h3>{{ number_format($awaitingResponse) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Talep No</th>
                            <th>Konu</th>
                            <th>Taraf Firma</th>
                            <th>Tipi</th>
                            <th>Talep Edilen Tutar</th>
                            <th>Talep Edilen Süre</th>
                            <th>Durum</th>
                            <th>Sorumlu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($claims as $c)
                        <tr onclick="location.href='{{ route('contracts.claims.show',$c->id) }}'" style="cursor:pointer">
                            <td>{{ $c->number }}</td>
                            <td>{{ $c->title }}</td>
                            <td>{{ $c->claimant }}</td>
                            <td>{{ $c->type }}</td>
                            <td>{{ $c->amount_requested ? number_format($c->amount_requested,2,',','.') . ' TL' : '-' }}</td>
                            <td>{{ $c->days_requested ? $c->days_requested . ' Gün' : '-' }}</td>
                            <td>{{ $c->status }}</td>
                            <td>{{ optional($c->responsible)->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $claims->links() }}</div>
        </div>
    </div>
</div>
@endsection
