@extends('layouts.master')

@section('title', 'Çöpteki Projeler')
@section('page-title', 'Çöpteki Projeler')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-trash-can-outline me-2"></i>Çöpteki Projeler
                </h5>
                <a href="{{ route('portfolio.all-projects') }}" class="btn btn-outline-primary">
                    <i class="mdi mdi-arrow-left"></i> Tüm Projeler
                </a>
            </div>
            <div class="card-body">
                @if($projects->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Proje Adı</th>
                                    <th>Proje Müdürü</th>
                                    <th>Durum</th>
                                    <th>Silinme Tarihi</th>
                                    <th class="text-center">Aksiyonlar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $project)
                                    <tr>
                                        <td>{{ $project->name }}</td>
                                        <td>{{ $project->project_manager ?? '-' }}</td>
                                        <td>{{ $project->status ?? '-' }}</td>
                                        <td>{{ $project->deleted_at ? $project->deleted_at->diffForHumans() : '-' }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('portfolio.project.restore', $project->id) }}" method="POST" style="display:inline-block">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Geri Al"><i class="mdi mdi-restore"></i></button>
                                            </form>
                                            <form action="{{ route('portfolio.project.force-delete', $project->id) }}" method="POST" style="display:inline-block" class="force-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger btn-force-delete" title="Kalıcı Sil"><i class="mdi mdi-delete-forever"></i></button>
                                            </form>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-force-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = btn.closest('form');
            Swal.fire({
                title: 'Emin misiniz?',
                text: 'Bu proje kalıcı olarak silinecek. Bu işlem geri alınamaz!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Son Onay',
                        text: 'Bu projeyi kalıcı olarak silmek istediğinizden kesinlikle emin misiniz?',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Evet, tamamen sil!',
                        cancelButtonText: 'Vazgeç'
                    }).then((final) => {
                        if (final.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    });
});
</script>
@endpush
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $projects->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="avatar-xl mx-auto mb-4">
                            <span class="avatar-title bg-light text-muted rounded-circle">
                                <i class="mdi mdi-trash-can-outline font-size-48"></i>
                            </span>
                        </div>
                        <h4 class="text-muted mb-3">Çöpte hiç proje yok</h4>
                        <a href="{{ route('portfolio.all-projects') }}" class="btn btn-primary btn-lg px-4">
                            <i class="mdi mdi-arrow-left"></i> Tüm Projeler
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
