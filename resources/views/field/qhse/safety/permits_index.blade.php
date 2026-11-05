@extends('layouts.master')

@section('title', 'Çalışma İzinleri')
@section('topbar-title', 'İSG')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Çalışma İzinleri</h4>
                <div class="page-title-right">
                    <a href="{{ route('field.qhse.safety.permit.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Yeni İzin Oluştur
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- İstatistik Kartları -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success rounded">
                                <i class="mdi mdi-check-circle"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Aktif İzinler</p>
                            <h4 class="mb-0">{{ $permits->where('status', 'active')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning rounded">
                                <i class="mdi mdi-clock-outline"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Onay Bekleyen</p>
                            <h4 class="mb-0">{{ $permits->where('status', 'pending')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info rounded">
                                <i class="mdi mdi-calendar-check"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Bu Ay Onaylanan</p>
                            <h4 class="mb-0">{{ $permits->where('status', 'active')->where('approved_at', '>=', now()->startOfMonth())->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-danger rounded">
                                <i class="mdi mdi-close-circle"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Reddedilen</p>
                            <h4 class="mb-0">{{ $permits->where('status', 'rejected')->count() }}</h4>
                        </div>
                    </div>
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
                            <label for="type_filter" class="form-label">İzin Tipi</label>
                            <select name="type" id="type_filter" class="form-select">
                                <option value="">Tümü</option>
                                <option value="yuksekte_calisma" {{ request('type') == 'yuksekte_calisma' ? 'selected' : '' }}>Yüksekte Çalışma</option>
                                <option value="sicak_islem" {{ request('type') == 'sicak_islem' ? 'selected' : '' }}>Sıcak İşlem</option>
                                <option value="kapali_alan" {{ request('type') == 'kapali_alan' ? 'selected' : '' }}>Kapalı Alan</option>
                                <option value="elektrik" {{ request('type') == 'elektrik' ? 'selected' : '' }}>Elektrik</option>
                                <option value="kazma_hafriyat" {{ request('type') == 'kazma_hafriyat' ? 'selected' : '' }}>Kazma/Hafriyat</option>
                                <option value="diger" {{ request('type') == 'diger' ? 'selected' : '' }}>Diğer</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status_filter" class="form-label">Durum</label>
                            <select name="status" id="status_filter" class="form-select">
                                <option value="">Tümü</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Onay Bekliyor</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Süresi Doldu</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Kapandı</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Reddedildi</option>
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

    <!-- İzinler Listesi -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Çalışma İzinleri Listesi ({{ $permits->total() }} kayıt)</h5>
                </div>
                <div class="card-body">
                    @forelse($permits as $permit)
                    <div class="permit-item border rounded p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm flex-shrink-0 me-3">
                                        <span class="avatar-title bg-primary rounded">
                                            <i class="mdi mdi-file-certificate"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">#{{ $permit->id }}</h6>
                                        <small class="text-muted">{{ $permit->created_at->format('d.m.Y') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <span class="badge bg-secondary">
                                    {{ $permit->type === 'yuksekte_calisma' ? 'Yüksekte Çalışma' :
                                       ($permit->type === 'sicak_islem' ? 'Sıcak İşlem' :
                                       ($permit->type === 'kapali_alan' ? 'Kapalı Alan' :
                                       ($permit->type === 'elektrik' ? 'Elektrik' :
                                       ($permit->type === 'kazma_hafriyat' ? 'Kazma/Hafriyat' : 'Diğer')))) }}
                                </span>
                            </div>

                            <div class="col-md-3">
                                <div>
                                    <strong>{{ Str::limit($permit->description, 40) }}</strong>
                                    <br>
                                    <small class="text-muted"><x-location-display :location="$permit->location" /></small>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div>
                                    <small class="text-muted d-block">Geçerlilik:</small>
                                    <strong>{{ $permit->valid_from ? \Carbon\Carbon::parse($permit->valid_from)->format('d.m.Y H:i') : 'Belirtilmemiş' }}</strong>
                                    <br>
                                    <small class="text-muted">→ {{ $permit->valid_to ? \Carbon\Carbon::parse($permit->valid_to)->format('d.m.Y H:i') : 'Belirtilmemiş' }}</small>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <span class="badge bg-{{ $permit->status === 'active' ? 'success' :
                                                       ($permit->status === 'pending' ? 'warning' :
                                                       ($permit->status === 'rejected' ? 'danger' :
                                                       ($permit->status === 'expired' ? 'secondary' : 'info'))) }}">
                                    {{ $permit->status === 'active' ? 'Aktif' :
                                       ($permit->status === 'pending' ? 'Onay Bekliyor' :
                                       ($permit->status === 'rejected' ? 'Reddedildi' :
                                       ($permit->status === 'expired' ? 'Süresi Doldu' : 'Kapandı'))) }}
                                </span>
                                @if($permit->approved_at)
                                <br><small class="text-success">Onay: {{ $permit->approved_at->format('d.m.Y') }}</small>
                                @endif
                            </div>

                            <div class="col-md-1 text-end">
                                <a href="{{ route('field.qhse.safety.permit.show', $permit) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="mdi mdi-file-certificate-outline" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="mt-3 text-muted">Henüz çalışma izni bulunmamaktadır.</h5>
                        <p class="text-muted">İlk izni oluşturmak için yukarıdaki "Yeni İzin Oluştur" butonunu kullanın.</p>
                    </div>
                    @endforelse

                    <!-- Sayfalama -->
                    @if(method_exists($permits, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $permits->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.permit-item:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s;
}
</style>
@endsection
