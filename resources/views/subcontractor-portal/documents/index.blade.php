@extends('subcontractor-portal.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">İSG ve Personel Evrakları</h2>
    <a href="{{ route('subcontractor.documents.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-2"></i>Yeni Belge Yükle
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Personel</th>
                        <th>Belge Türü</th>
                        <th>Yükleme Tarihi</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>{{ $doc->worker->name ?? 'Bilinmeyen Personel' }}</td>
                        <td>
                            @if($doc->document_type == 'sgk') SGK İşe Giriş
                            @elseif($doc->document_type == 'isg') İSG Eğitimi
                            @else Sağlık Raporu @endif
                        </td>
                        <td>{{ $doc->created_at->format('d.m.Y') }}</td>
                        <td>
                            @if($doc->status == 'onaylandi' || $doc->is_valid)
                                <span class="badge bg-success">Onaylandı (Geçerli)</span>
                            @else
                                <span class="badge bg-warning text-dark">Onay Bekliyor</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Henüz belge yüklenmemiş.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
