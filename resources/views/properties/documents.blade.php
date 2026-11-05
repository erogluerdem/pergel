@extends('layouts.master')

@section('title', $property->property_code . ' - Belge Yönetimi')
@section('topbar-title', 'Operasyon')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-folder-open" style="font-size: 1.5rem; color: #0d6efd;"></i>
                <h4 class="mb-0">Belge Yönetimi</h4>
                <span class="badge bg-info">{{ $property->property_code }}</span>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                <i class="fas fa-cloud-upload-alt me-2"></i>Belge Yükle
            </button>
        </div>
    </div>

    {{-- Belge Yükleme Formu Modal --}}
    <div class="modal fade" id="uploadDocumentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Belge Yükle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('operations.properties.documents.upload', $property) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Belge Adı *</label>
                            <input type="text" name="document_name" class="form-control @error('document_name') is-invalid @enderror" placeholder="Belge adı" required>
                            @error('document_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Belge Kategorisi *</label>
                            <select name="document_category" class="form-select @error('document_category') is-invalid @enderror" required>
                                <option value="">Kategori Seçiniz</option>
                                <option value="deed">Tapu</option>
                                <option value="cadastral">Kadastro</option>
                                <option value="zoning">İmar</option>
                                <option value="permit">İzin</option>
                                <option value="survey">Ölçüm</option>
                                <option value="evaluation">Değerlendirme</option>
                                <option value="legal">Yasal</option>
                                <option value="correspondence">Yazışma</option>
                                <option value="contract">Sözleşme</option>
                                <option value="other">Diğer</option>
                            </select>
                            @error('document_category') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dosya *</label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                            <small class="text-muted">Maksimum dosya boyutu: 10 MB</small>
                            @error('file') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="Belge açıklaması"></textarea>
                            @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Belge Tarihi</label>
                                <input type="date" name="document_date" class="form-control @error('document_date') is-invalid @enderror">
                                @error('document_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Geçerlilik Tarihi</label>
                                <input type="date" name="validity_date" class="form-control @error('validity_date') is-invalid @enderror">
                                @error('validity_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_critical" class="form-check-input" id="isCritical">
                                <label class="form-check-label" for="isCritical">
                                    Kritik Belge
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="requires_renewal" class="form-check-input" id="requiresRenewal">
                                <label class="form-check-label" for="requiresRenewal">
                                    Yenileme Gerekli
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Yükle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Belgeler Listesi --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th width="20%">Belge Adı</th>
                        <th width="12%">Kategori</th>
                        <th width="12%">Dosya Tipi</th>
                        <th width="12%">Belge Tarihi</th>
                        <th width="12%">Geçerlilik</th>
                        <th width="8%">Boyut</th>
                        <th width="12%">Durum</th>
                        <th width="12%" class="text-end">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($property->documents ?? [] as $doc)
                        <tr>
                            <td>
                                <strong>{{ $doc->document_name }}</strong>
                                @if ($doc->is_critical)
                                    <br><span class="badge bg-danger">Kritik</span>
                                @endif
                                @if ($doc->requires_renewal)
                                    <br><span class="badge bg-warning">Yenileme Gerekli</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($doc->document_category ?? '-') }}</span>
                            </td>
                            <td>{{ strtoupper($doc->file_type) }}</td>
                            <td>{{ $doc->document_date?->format('d.m.Y') ?? '-' }}</td>
                            <td>
                                @if ($doc->validity_date)
                                    @if ($doc->validity_date->isPast())
                                        <span class="badge bg-danger">Süresi Geçti</span>
                                    @elseif ($doc->validity_date->diffInDays(now()) < 30)
                                        <span class="badge bg-warning">Yaklaşıyor</span>
                                    @else
                                        {{ $doc->validity_date->format('d.m.Y') }}
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td><small>{{ formatBytes($doc->file_size ?? 0) }}</small></td>
                            <td>
                                @if ($doc->validity_date && $doc->validity_date->isPast())
                                    <span class="badge bg-danger">Geçersiz</span>
                                @elseif ($doc->validity_date && $doc->validity_date->diffInDays(now()) < 30)
                                    <span class="badge bg-warning">Uyarı</span>
                                @else
                                    <span class="badge bg-success">Geçerli</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if (Storage::disk('private')->exists($doc->file_path))
                                    <a href="#" class="btn btn-sm btn-info" title="İndir">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#reminderModal{{ $doc->id }}" title="Hatırlatma Ekle">
                                    <i class="fas fa-bell"></i>
                                </button>
                                <form method="POST" action="#" style="display:inline;" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Sil">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Hatırlatma Modal --}}
                        <div class="modal fade" id="reminderModal{{ $doc->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Hatırlatma Ekle</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('operations.properties.reminder.add', $doc) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Hatırlatma Başlığı *</label>
                                                <input type="text" name="reminder_title" class="form-control" placeholder="Hatırlatma başlığı" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Açıklama</label>
                                                <textarea name="description" class="form-control" rows="2"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Hatırlatma Tarihi *</label>
                                                <input type="date" name="renewal_due_date" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Sıklık *</label>
                                                <select name="reminder_frequency" class="form-select" required>
                                                    <option value="one_time">Tek Seferlik</option>
                                                    <option value="weekly">Haftalık</option>
                                                    <option value="monthly">Aylık</option>
                                                    <option value="quarterly">Üç Aylık</option>
                                                    <option value="yearly">Yıllık</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                                            <button type="submit" class="btn btn-primary">Ekle</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted">Henüz belge yüklenmemiştir.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Buttons --}}
    <div class="mt-4">
        <a href="{{ route('operations.properties.show', $property) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Geri Dön
        </a>
    </div>
@endsection

@section('scripts')
    <script>
        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }
    </script>
@endsection
