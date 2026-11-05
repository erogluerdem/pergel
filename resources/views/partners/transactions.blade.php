@extends('layouts.master')

@section('title','Ortak İşlemleri')

@section('content')
<div class="container">
    <h2>Ortak İşlemleri</h2>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="partner_id" class="form-select">
                <option value="">Tüm Ortaklar</option>
                @foreach($partners as $p)
                <option value="{{ $p->id }}" {{ request('partner_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <input type="date" name="from" class="form-control" value="{{ request('from') }}" placeholder="from">
        </div>
        <div class="col-auto">
            <input type="date" name="to" class="form-control" value="{{ request('to') }}" placeholder="to">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary">Filtrele</button>
        </div>
        <div class="col-auto">
            <a href="{{ route('partners.transactions') }}" class="btn btn-outline-secondary">Sıfırla</a>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Ortak</th>
                <th>Tarih</th>
                <th>Tutar</th>
                <th>Kaynak</th>
                <th>Hesap</th>
                <th>Kur</th>
                <th>Tutar (TL)</th>
                <th>Yön</th>
                <th>Açıklama</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $tx)
            <tr>
                <td>{{ $tx->id }}</td>
                <td>{{ $tx->partner->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($tx->transaction_date)->format('d.m.Y') }}</td>
                <td>{{ number_format($tx->amount,2,',','.') }}</td>
                <td>{{ $tx->source }}</td>
                <td>{{ optional($tx->cashBankTransaction->account)->bank_name ? optional($tx->cashBankTransaction->account)->bank_name . ' - ' . optional($tx->cashBankTransaction->account)->name : optional($tx->cashBankTransaction->account)->name ?? '-' }}</td>
                <td>{{ optional($tx->cashBankTransaction)->currency ?? 'TRY' }}</td>
                <td class="text-end">{{ number_format(optional($tx->cashBankTransaction)->amount_tl ?? null, 2, ',', '.') }}</td>
                <td>
                    @php $dir = optional($tx->cashBankTransaction)->direction ?? 'in'; @endphp
                    @if($dir === 'in')
                        <span class="badge bg-success">Alış</span>
                    @else
                        <span class="badge bg-danger">Veriş</span>
                    @endif
                </td>
                <td>{{ $tx->description }}</td>
                <td>
                    <a href="{{ route('partners.transactions.edit', $tx->id) }}" class="btn btn-sm btn-primary" aria-label="İşlem {{ $tx->id }} düzenle">Düzenle</a>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $tx->id }}" aria-label="İşlem {{ $tx->id }} sil">Sil</button>
                    <form id="delete-form-{{ $tx->id }}" action="{{ route('partners.transactions.delete', $tx->id) }}" method="POST" style="display:none">
                        @csrf
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    {{ $transactions->links() }}
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu işlemi silmek istediğinize emin misiniz? Bu işlem geri alınamaz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });
    });
});
</script>
@endsection
