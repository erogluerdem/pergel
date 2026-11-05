@extends('layouts.master')
@section('title', 'Müşteri Geri Dönüşüm Kutusu')
@section('topbar-title', 'Finans - Müşteri Geri Dönüşüm')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Geri Dönüşüm Kutusu</h3>
        <a href="{{ route('finance.accounts.customers') }}" class="btn btn-secondary">Geri Dönüş</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ad</th>
                        <th>Silinme Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customersPaged as $c)
                    <tr>
                        <td>{{ $c->name }}</td>
                        <td>{{ $c->deleted_at }}</td>
                        <td>
                            <form method="POST" action="{{ route('finance.accounts.customers.restore', ['id'=>$c->id]) }}" style="display:inline-block">
                                @csrf
                                <button class="btn btn-sm btn-outline-success">Geri Yükle</button>
                            </form>
                            <form method="POST" action="{{ route('finance.accounts.customers.destroy', ['id'=>$c->id]) }}" style="display:inline-block" onsubmit="return confirm('Bu kaydı kalıcı olarak silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="permanent" value="1" />
                                <button class="btn btn-sm btn-danger">Kalıcı Sil</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($customersPaged->hasPages())
                <div class="mt-3">
                    {{ $customersPaged->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
