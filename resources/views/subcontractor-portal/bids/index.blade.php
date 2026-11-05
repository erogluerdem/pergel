@extends('subcontractor-portal.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">İhale & Tekliflerim</h2>
    <a href="{{ route('subcontractor.bids.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-gavel me-2"></i>Açık İhaleleri Gör
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>İhale Ref.</th>
                        <th>Teklif Tutarı</th>
                        <th>Tarih</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bids as $bid)
                    <tr>
                        <td>İhale #{{ $bid->tender_id }}</td>
                        <td class="fw-semibold">{{ number_format($bid->bid_amount, 2, ',', '.') }} {{ $bid->currency }}</td>
                        <td>{{ $bid->created_at->format('d.m.Y') }}</td>
                        <td>
                            @if($bid->status == 'kazandi')
                                <span class="badge bg-success">Kazandı / Sözleşmeye Döndü</span>
                            @elseif($bid->status == 'elendi')
                                <span class="badge bg-danger">Elendi</span>
                            @else
                                <span class="badge bg-warning text-dark">Değerlendirmede</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Henüz hiçbir ihaleye teklif vermediniz.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
