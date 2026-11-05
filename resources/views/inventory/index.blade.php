@extends('layouts.master')

@section('title')
    Envanter Yönetimi
@endsection

@section('topbar-title')
    Envanter
@endsection

@section('content')
<!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="fa fa-boxes float-end"></i>
                    <h6 class="text-uppercase mt-0">Toplam Öğe</h6>
                    <h2 class="m-b-20" data-plugin="counterup">{{ $stats['total'] }}</h2>
                    <span class="badge bg-success"> +2.5% </span>
                    <span class="text-muted">Geçen hafta</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="fa fa-lira-sign float-end"></i>
                    <h6 class="text-uppercase mt-0">Toplam Değer</h6>
                    <h2 class="m-b-20">₺{{ number_format($stats['total_value'], 2, ',', '.') }}</h2>
                    <span class="badge bg-info"> +11.7% </span>
                    <span class="text-muted">Bu ay</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="fa fa-cubes float-end"></i>
                    <h6 class="text-uppercase mt-0">Toplam Stok</h6>
                    <h2 class="m-b-20" data-plugin="counterup">{{ $stats['total'] }}</h2>
                    <span class="badge bg-warning"> -0.5% </span>
                    <span class="text-muted">Bu hafta</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card tilebox-one">
                <div class="card-body">
                    <i class="fa fa-exclamation-triangle float-end"></i>
                    <h6 class="text-uppercase mt-0">Kritik Stok</h6>
                    <h2 class="m-b-20" data-plugin="counterup">{{ $stats['low_stock'] }}</h2>
                    <span class="badge bg-danger"> +3% </span>
                    <span class="text-muted">Dikkat gerekli</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <h4 class="card-title">Envanter Listesi</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <div class="btn-group">
                                <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus me-1"></i>Yeni Öğe Ekle
                                </a>
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fa fa-download me-1"></i>Dışa Aktar
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#"><i class="fa fa-file-excel me-2"></i>Excel</a>
                                    <a class="dropdown-item" href="#"><i class="fa fa-file-pdf me-2"></i>PDF</a>
                                    <a class="dropdown-item" href="#"><i class="fa fa-file-csv me-2"></i>CSV</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" name="category">
                                <option value="">Tüm Kategoriler</option>
                                <option value="Teknoloji">Teknoloji</option>
                                <option value="Mobilya">Mobilya</option>
                                <option value="Görsel/İşitsel">Görsel/İşitsel</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">Tüm Durumlar</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Pasif">Pasif</option>
                                <option value="Bakımda">Bakımda</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Öğe adı veya kodu ile ara..." name="search">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kod</th>
                                    <th>Öğe Adı</th>
                                    <th>Kategori</th>
                                    <th>Miktar</th>
                                    <th>Birim Fiyat</th>
                                    <th>Toplam Değer</th>
                                    <th>Konum</th>
                                    <th>Durum</th>
                                    <th>Son Güncelleme</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td><span class="badge bg-info">{{ $item->code }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <span class="avatar-title bg-primary rounded-circle">
                                                    <i class="fa fa-box"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $item->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $item->category }}</span>
                                    </td>
                                    <td><strong>{{ $item->quantity }}</strong></td>
                                    <td>₺{{ number_format($item->price, 2, ',', '.') }}</td>
                                    <td><strong>₺{{ number_format($item->totalValue(), 2, ',', '.') }}</strong></td>
                                    <td>
                                        <small class="text-muted">{{ $item->location }}</small>
                                    </td>
                                    <td>
                                        @if($item->status == 'active')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($item->status == 'inactive')
                                            <span class="badge bg-warning">Pasif</span>
                                        @elseif($item->status == 'discontinued')
                                            <span class="badge bg-danger">Durmuş</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $item->updated_at->format('d.m.Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('inventory.show', $item->id) }}" class="btn btn-outline-primary" title="Detay">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-outline-secondary" title="Düzenle">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" title="Sil" onclick="deleteItem({{ $item->id }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($items->isEmpty())
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light rounded-circle">
                                <i class="fa fa-boxes fa-2x text-muted"></i>
                            </div>
                        </div>
                        <h5 class="text-muted">Henüz envanter öğesi bulunmuyor</h5>
                        <p class="text-muted">İlk envanter öğenizi eklemek için "Yeni Öğe Ekle" butonunu kullanın.</p>
                        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i>İlk Öğeyi Ekle
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Öğeyi Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bu envanter öğesini silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Sil</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteItem(id) {
    $('#deleteModal').modal('show');
    $('#confirmDelete').off('click').on('click', function() {
        // AJAX delete request would go here
        $('#deleteModal').modal('hide');
        // Refresh page or update table
        location.reload();
    });
}

// Filter functionality
$('select[name="category"], select[name="status"]').on('change', function() {
    // Apply filters - would normally send AJAX request
    console.log('Filter applied');
});

$('input[name="search"]').on('keyup', function() {
    // Search functionality - would normally send AJAX request
    console.log('Search:', $(this).val());
});
</script>
@endpush