@extends('layouts.master')

@section('title', 'Sözleşme Listesi')

@section('topbar-title', 'Taşeron Yönetimi')

@section('css')
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filtreleme ve Arama -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filtreleme ve Arama</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('subcontractor.contracts.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Arama</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Sözleşme no, ad veya taşeron adı">
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Durum</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tümü</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Feshedildi</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="contract_type" class="form-label">Sözleşme Tipi</label>
                            <select class="form-select" id="contract_type" name="contract_type">
                                <option value="">Tümü</option>
                                <option value="unit_price" {{ request('contract_type') == 'unit_price' ? 'selected' : '' }}>Birim Fiyat</option>
                                <option value="lump_sum" {{ request('contract_type') == 'lump_sum' ? 'selected' : '' }}>Götürü Bedel</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">
                                <i class="mdi mdi-magnify"></i> Filtrele
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <a href="{{ route('subcontractor.contracts.index') }}" class="btn btn-outline-secondary d-block">
                                <i class="mdi mdi-refresh"></i> Temizle
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sözleşme Listesi -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Taşeron Sözleşmeleri ({{ $contracts->total() }} sözleşme)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sözleşme No / Adı</th>
                                    <th>Proje</th>
                                    <th>Taşeron Firma</th>
                                    <th>Sözleşme Bedeli</th>
                                    <th>Ödenen Tutar</th>
                                    <th>Kalan Bakiye</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contracts as $contract)
                                <tr>
                                    <td>
                                        <a href="{{ route('subcontractor.contracts.show', $contract->id) }}" class="text-decoration-none">
                                            {{ $contract->contract_number }}<br>
                                            <small class="text-muted">{{ $contract->contract_name }}</small>
                                        </a>
                                    </td>
                                    <td>{{ $contract->project->name ?? 'N/A' }}</td>
                                    <td>{{ $contract->subcontractor->company_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($contract->total_amount, 0, ',', '.') }} TL</td>
                                    <td>{{ number_format($contract->paid_amount, 0, ',', '.') }} TL</td>
                                    <td>{{ number_format($contract->remaining_amount, 0, ',', '.') }} TL</td>
                                    <td>
                                        @switch($contract->status)
                                            @case('draft')
                                                <span class="badge bg-secondary">Taslak</span>
                                                @break
                                            @case('active')
                                                <span class="badge bg-success">Aktif</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-info">Tamamlandı</span>
                                                @break
                                            @case('terminated')
                                                <span class="badge bg-danger">Feshedildi</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('subcontractor.contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-eye"></i> Detay
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="mdi mdi-file-document-outline font-size-48 text-muted mb-2"></i>
                                        <h5>Henüz sözleşme bulunmuyor</h5>
                                        <p class="text-muted">Yeni taşeron sözleşmeleri eklemek için sözleşme oluşturma butonunu kullanabilirsiniz.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Sayfalama -->
                    @if($contracts->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $contracts->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
