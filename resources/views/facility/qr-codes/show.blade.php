@extends('layouts.master')

@section('title', 'QR Kod Detayları')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-qrcode"></i> QR Kod Detayları
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('facility.qr.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            {{-- QR Kod Görüntüsü --}}
            <div class="card mb-4">
                <div class="card-body text-center">
                    <h6 class="card-title">QR Kodu</h6>
                    @if($qrCode && $qrCode->qr_code_path && file_exists(public_path($qrCode->qr_code_path)))
                        <img src="{{ asset($qrCode->qr_code_path) }}" alt="QR Kod" class="img-fluid" style="max-width: 300px;">
                    @else
                        <div class="bg-light p-4 rounded">
                            <i class="fas fa-image text-muted fa-3x"></i>
                            <p class="text-muted mt-2">QR Kodu yok</p>
                        </div>
                    @endif
                    
                    @if($qrCode)
                        <div class="mt-3 d-grid gap-2">
                            <a href="{{ route('facility.qr.download', $qrCode->id) }}" class="btn btn-primary">
                                <i class="fas fa-download"></i> İndir
                            </a>
                            <a href="{{ route('facility.qr.download', $qrCode->id) }}?format=pdf" class="btn btn-info">
                                <i class="fas fa-file-pdf"></i> PDF İndir
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- İstatistikler --}}
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">İstatistikler</h6>
                    
                    <div class="mb-3">
                        <small class="text-muted">Toplam Taramalar</small>
                        <div class="h4 mb-0">{{ $qrCode->scans_count ?? 0 }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Son Taranan</small>
                        <div>
                            @if($qrCode->last_scanned_at)
                                <strong>{{ $qrCode->last_scanned_at->format('d.m.Y H:i') }}</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Oluşturulma Tarihi</small>
                        <div>
                            <strong>{{ $qrCode->created_at->format('d.m.Y H:i') }}</strong>
                        </div>
                    </div>

                    <div class="mb-0">
                        <small class="text-muted">Durum</small>
                        <div>
                            @if($qrCode->is_active)
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Aktif</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-ban"></i> Deaktif</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            {{-- Detay Bilgiler --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Genel Bilgiler</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">QR Token</small>
                            <code class="bg-light p-2 rounded d-block text-break">{{ $qrCode->qr_token }}</code>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Etiket</small>
                            <strong>{{ $qrCode->qr_label ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Proje</small>
                            <strong>
                                @if($qrCode->project)
                                    <a href="{{ route('projects.show', $qrCode->project->id) }}">
                                        {{ $qrCode->project->name }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Ünite</small>
                            <strong>
                                @if($qrCode->unit)
                                    {{ $qrCode->unit->unit_number }} - {{ $qrCode->unit->unit_type }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- İşlemler --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">İşlemler</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2 d-md-flex">
                        @if($qrCode->is_active)
                            <form action="{{ route('facility.qr.disable', $qrCode->id) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100" onclick="return confirm('QR kodu deaktif etmek istediğinize emin misiniz?')">
                                    <i class="fas fa-pause"></i> Deaktif Et
                                </button>
                            </form>
                        @else
                            <form action="{{ route('facility.qr.enable', $qrCode->id) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-play"></i> Aktif Et
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tarama Geçmişi --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Son Taramalar (Son 10)</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">Tarih/Saat</th>
                                <th width="55%">IP Adresi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($scanHistory) && count($scanHistory) > 0)
                                @foreach($scanHistory as $scan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $scan['scanned_at'] ?? '-' }}</td>
                                        <td><code>{{ $scan['ip_address'] ?? '-' }}</code></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox"></i> Tarama geçmişi yok
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
