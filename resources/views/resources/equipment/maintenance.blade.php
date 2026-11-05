@extends('layouts.master')

@section('title', $equipment->name . ' - Bakım Yönetimi')

@section('topbar-title', 'Ekipman Parkı')

@section('content')

<!-- Bakım Ekleme Formu -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Yeni Bakım Kaydı Ekle</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('resources.equipment.maintenance.store', $equipment) }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="maintenance_date" class="form-label">Bakım Tarihi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('maintenance_date') is-invalid @enderror"
                                       id="maintenance_date" name="maintenance_date" value="{{ old('maintenance_date', date('Y-m-d')) }}" required>
                                @error('maintenance_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Bakım Tipi <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Periyodik" {{ old('type') === 'Periyodik' ? 'selected' : '' }}>Periyodik Bakım</option>
                                    <option value="Arıza" {{ old('type') === 'Arıza' ? 'selected' : '' }}>Arıza Onarımı</option>
                                    <option value="Acil" {{ old('type') === 'Acil' ? 'selected' : '' }}>Acil Bakım</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Başlık <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title') }}" placeholder="Örn: Yağ değişimi, Filtre değişimi" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="performed_by" class="form-label">Bakımı Yapan</label>
                                <input type="text" class="form-control @error('performed_by') is-invalid @enderror"
                                       id="performed_by" name="performed_by" value="{{ old('performed_by') }}" placeholder="Firma adı veya kişi">
                                @error('performed_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cost" class="form-label">Maliyet (₺)</label>
                                <input type="number" class="form-control @error('cost') is-invalid @enderror"
                                       id="cost" name="cost" value="{{ old('cost') }}" step="0.01" min="0" placeholder="0.00">
                                @error('cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="working_hours_before" class="form-label">Bakım Öncesi Çalışma Saati</label>
                                <input type="number" class="form-control @error('working_hours_before') is-invalid @enderror"
                                       id="working_hours_before" name="working_hours_before" value="{{ old('working_hours_before') }}" min="0">
                                @error('working_hours_before')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="working_hours_after" class="form-label">Bakım Sonrası Çalışma Saati</label>
                                <input type="number" class="form-control @error('working_hours_after') is-invalid @enderror"
                                       id="working_hours_after" name="working_hours_after" value="{{ old('working_hours_after') }}" min="0">
                                @error('working_hours_after')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parts_used" class="form-label">Kullanılan Parçalar</label>
                                <textarea class="form-control @error('parts_used') is-invalid @enderror"
                                          id="parts_used" name="parts_used" rows="3"
                                          placeholder="Değiştirilen parçaları listeleyin (örn: Yağ filtresi, Hava filtresi)">{{ old('parts_used') }}</textarea>
                                @error('parts_used')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Her parçayı ayrı satırda yazın</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">Açıklama</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3"
                                          placeholder="Bakım detaylarını açıklayın">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Ek Notlar</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes" name="notes" rows="2"
                                          placeholder="Ek bilgiler">{{ old('notes') }}</textarea>
                                @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save me-1"></i> Bakım Kaydını Ekle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bakım Geçmişi -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Bakım Geçmişi</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap">
                        <thead>
                            <tr>
                                <th>Tarih</th>
                                <th>Tip</th>
                                <th>Başlık</th>
                                <th>Maliyet</th>
                                <th>Çalışma Saati</th>
                                <th>Yapan</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($maintenances as $maintenance)
                            <tr>
                                <td>{{ $maintenance->maintenance_date->format('d.m.Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $maintenance->type === 'Arıza' ? 'danger' : ($maintenance->type === 'Acil' ? 'warning' : 'info') }}">
                                        {{ $maintenance->type }}
                                    </span>
                                </td>
                                <td>{{ $maintenance->title }}</td>
                                <td>{{ $maintenance->cost ? number_format($maintenance->cost, 2) . ' ₺' : '-' }}</td>
                                <td>
                                    @if($maintenance->working_hours_before && $maintenance->working_hours_after)
                                    {{ $maintenance->working_hours_before }} → {{ $maintenance->working_hours_after }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ $maintenance->performed_by ?: '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" onclick="viewMaintenanceDetail({{ $maintenance->id }})">
                                        <i class="mdi mdi-eye"></i> Detay
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="mdi mdi-wrench-off display-4"></i>
                                    <p class="mt-2">Henüz bakım kaydı bulunmuyor.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Sayfalama -->
                @if($maintenances->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $maintenances->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bakım Detay Modal -->
<div class="modal fade" id="maintenanceDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bakım Detayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="maintenanceDetailContent">
                <!-- AJAX ile doldurulacak -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewMaintenanceDetail(maintenanceId) {
    // AJAX ile bakım detayını getir - ileride eklenecek
    alert('Bakım detay özelliği yakında eklenecek. ID: ' + maintenanceId);
}
</script>
@endsection
