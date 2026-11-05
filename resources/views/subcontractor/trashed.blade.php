@extends('layouts.master')

@section('title','Silinmiş Taşeronlar')
@section('topbar-title','Taşeron Yönetimi')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Silinmiş Taşeronlar</h4>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Firma</th>
                            <th>Silinme Tarihi</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trashed as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->company_name }}</td>
                                <td>{{ optional($t->deleted_at)->format('Y-m-d H:i') }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('subcontractor.restore', $t->id) }}" style="display:inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Geri Al</button>
                                    </form>
                                    <form method="POST" action="{{ route('subcontractor.force_delete', $t->id) }}" style="display:inline" class="js-confirm-delete">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">Kalıcı Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Silinmiş taşeron bulunamadı.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $trashed->links() }}</div>
        </div>
    </div>
</div>
        <script>
        // SweetAlert2 attach for .js-confirm-delete (force delete confirmation)
        (function(){
            function ensureSweetAlert(cb){
                if(window.Swal) return cb();
                var s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js';
                s.onload = cb;
                document.head.appendChild(s);
            }
            ensureSweetAlert(function(){
                document.querySelectorAll('form.js-confirm-delete').forEach(function(form){
                    form.addEventListener('submit', function(e){
                        e.preventDefault();
                        var title = form.closest('tr')?.querySelector('td:nth-child(2)')?.innerText || 'bu kaydı';
                        Swal.fire({
                            title: 'Kalıcı silme işlemi',
                            html: '<strong>' + title + '</strong><br>Bu işlem geri alınamaz. Devam edilsin mi?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Evet, kalıcı sil',
                            cancelButtonText: 'İptal',
                            reverseButtons: true,
                        }).then(function(result){ if(result.isConfirmed){ form.submit(); } });
                    });
                });
            });
        })();
        </script>

        @endsection
