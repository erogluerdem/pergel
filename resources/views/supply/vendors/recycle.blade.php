@extends('layouts.master')
@section('title','Tedarikçiler - Geri Dönüşüm')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-trash-restore text-secondary me-2"></i> Geri Dönüşüm Kutusu</h4>
    <a href="{{ route('finance.accounts.suppliers') }}" class="btn btn-outline-primary">Geri Dönüşüme Git</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Firma</th>
                            <th>Silinme Tarihi</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $v)
                            <tr>
                                <td>{{ $v->name }}</td>
                                <td>{{ $v->deleted_at ? $v->deleted_at->diffForHumans() : '-' }}</td>
                                <td>
                                    <form action="{{ route('finance.accounts.suppliers.restore', $v->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Geri Yükle</button>
                                    </form>
                                    <form action="{{ route('finance.accounts.suppliers.destroy', $v->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bu kaydı kalıcı olarak silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="permanent" value="1">
                                        <button class="btn btn-sm btn-danger">Kalıcı Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3">Geri dönüşüm kutusunda kayıt yok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $vendors->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
