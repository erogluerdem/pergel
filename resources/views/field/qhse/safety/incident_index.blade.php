@extends('layouts.master')

@section('title', 'Olay ve Kaza Raporları')
@section('topbar-title', 'İSG')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Olay ve Kaza Raporları</h4>
                <div class="page-title-right">
                    <a href="{{ route('field.qhse.safety.incident.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Yeni Olay Bildir
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="type_filter" class="form-label">Olay Tipi</label>
                            <select name="type" id="type_filter" class="form-select">
                                <option value="">Tümü</option>
                                <option value="ramak_kala" {{ request('type') == 'ramak_kala' ? 'selected' : '' }}>Ramak Kala</option>
                                <option value="hafif_yaralanmali" {{ request('type') == 'hafif_yaralanmali' ? 'selected' : '' }}>Hafif Yaralanmalı</option>
                                <option value="agir_yaralanmali" {{ request('type') == 'agir_yaralanmali' ? 'selected' : '' }}>Ağır Yaralanmalı</option>
                                <option value="guvensiz_durum" {{ request('type') == 'guvensiz_durum' ? 'selected' : '' }}>Güvensiz Durum</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status_filter" class="form-label">Durum</label>
                            <select name="status" id="status_filter" class="form-select">
                                <option value="">Tümü</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Açık</option>
                                <option value="investigating" {{ request('status') == 'investigating' ? 'selected' : '' }}>Araştırılıyor</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Kapalı</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Tarih Aralığı</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="mdi mdi-filter"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Olay Listesi -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Olay Listesi ({{ $incidents->total() }} kayıt)</h5>
                </div>
                <div class="card-body">
                    @forelse($incidents as $incident)
                    <div class="incident-item border rounded p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm flex-shrink-0 me-3">
                                        @if($incident->evidence_path)
                                        <img src="{{ asset('storage/' . $incident->evidence_path) }}"
                                             alt="Kanıt" class="avatar-sm rounded">
                                        @else
                                        <span class="avatar-title bg-light text-primary rounded">
                                            <i class="mdi mdi-camera"></i>
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">#{{ $incident->id }}</h6>
                                        <small class="text-muted">{{ $incident->occurred_at ? $incident->occurred_at->format('d.m.Y H:i') : $incident->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <span class="badge bg-{{ $incident->type === 'ramak_kala' ? 'warning' : ($incident->type === 'hafif_yaralanmali' ? 'info' : ($incident->type === 'agir_yaralanmali' ? 'danger' : 'secondary')) }}">
                                    {{ $incident->type === 'ramak_kala' ? 'Ramak Kala' : ($incident->type === 'hafif_yaralanmali' ? 'Hafif Yaralanma' : ($incident->type === 'agir_yaralanmali' ? 'Ağır Yaralanma' : 'Güvensiz Durum')) }}
                                </span>
                            </div>

                            <div class="col-md-3">
                                <div>
                                    <strong>{{ Str::limit($incident->description, 50) }}</strong>
                                    <br>
                                    <small class="text-muted"><x-location-display :location="$incident->location" /></small>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <span class="badge bg-{{ $incident->status === 'open' ? 'danger' : ($incident->status === 'investigating' ? 'warning' : 'success') }}">
                                    {{ $incident->status === 'open' ? 'Açık' : ($incident->status === 'investigating' ? 'Araştırılıyor' : 'Kapalı') }}
                                </span>
                                @if($incident->corrective_action)
                                <br><small class="text-success">DÖF: Var</small>
                                @endif
                            </div>

                            <div class="col-md-3 text-end">
                                <a href="{{ route('field.qhse.safety.incident.show', $incident) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-eye"></i> Detay
                                </a>
                                @if($incident->status !== 'closed')
                                <button class="btn btn-sm btn-outline-success ms-1" onclick="closeIncident({{ $incident->id }})">
                                    <i class="mdi mdi-check"></i> Kapat
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="mdi mdi-file-search-outline" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="mt-3 text-muted">Henüz olay bildirimi bulunmamaktadır.</h5>
                        <p class="text-muted">İlk olayı bildirmek için yukarıdaki "Yeni Olay Bildir" butonunu kullanın.</p>
                    </div>
                    @endforelse

                    <!-- Sayfalama -->
                    @if(method_exists($incidents, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $incidents->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function closeIncident(incidentId) {
    if (confirm('Bu olayı kapatmak istediğinizden emin misiniz?')) {
        // AJAX ile olay kapatma işlemi
        fetch(`/field/qhse/safety/incidents/${incidentId}/close`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Hata oluştu!');
            }
        });
    }
}
</script>

<style>
.incident-item:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s;
}
</style>
@endsection
