@extends('subcontractor-portal.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">Avans & Ödeme Talepleri</h2>
    <a href="{{ route('subcontractor.advance_requests.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Yeni Talep Oluştur
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tarih</th>
                        <th>Tutar</th>
                        <th>Gerekçe</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                    <tr>
                        <td>{{ $req->requested_date->format('d.m.Y') }}</td>
                        <td class="fw-semibold">{{ number_format($req->amount, 2, ',', '.') }} {{ $req->currency }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($req->reason, 40) }}</td>
                        <td>
                            @if($req->status == 'onaylandi')
                                <span class="badge bg-success">Onaylandı</span>
                            @elseif($req->status == 'red')
                                <span class="badge bg-danger">Reddedildi</span>
                            @else
                                <span class="badge bg-warning text-dark">Değerlendirmede</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Geçmiş avans talebiniz bulunmuyor.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
