@extends('layouts.master')

@section('title','Madde Düzenle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Madde Düzenle: {{ $clause->title }}</h5>
                        <a href="{{ route('contracts.library.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('contracts.library.clauses.update', $clause->id) }}">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Madde Başlığı <span class="text-danger">*</span></label>
                                    <input name="title" class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title', $clause->title) }}" required>
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="category" class="form-select">
                                        <option value="">Seçiniz...</option>
                                        <option value="Süre" {{ $clause->category === 'Süre' ? 'selected' : '' }}>Süre ve Program</option>
                                        <option value="Ödeme" {{ $clause->category === 'Ödeme' ? 'selected' : '' }}>Ödeme ve Hakediş</option>
                                        <option value="Garanti" {{ $clause->category === 'Garanti' ? 'selected' : '' }}>Garanti ve Sorumluluk</option>
                                        <option value="İSG" {{ $clause->category === 'İSG' ? 'selected' : '' }}>İş Sağlığı ve Güvenliği</option>
                                        <option value="Sigorta" {{ $clause->category === 'Sigorta' ? 'selected' : '' }}>Sigorta ve Teminat</option>
                                        <option value="Fesih" {{ $clause->category === 'Fesih' ? 'selected' : '' }}>Fesih ve İptal</option>
                                        <option value="Genel" {{ $clause->category === 'Genel' ? 'selected' : '' }}>Genel Hükümler</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Madde İçeriği <span class="text-danger">*</span></label>
                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                                              rows="12" required>{{ old('content', $clause->content) }}</textarea>
                                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Maddenin detaylı açıklamasını yazın. Hukuki terimler ve süreler belirtin.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Durum</label>
                                    <select name="status" class="form-select">
                                        <option value="draft" {{ $clause->status === 'draft' ? 'selected' : '' }}>Taslak</option>
                                        <option value="active" {{ $clause->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                    </select>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-info">
                                        <i class="mdi mdi-content-save"></i> Güncelle
                                    </button>
                                    <a href="{{ route('contracts.library.index') }}" class="btn btn-secondary">İptal</a>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Madde Bilgileri</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>ID:</strong></td>
                                                <td>{{ $clause->id }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kategori:</strong></td>
                                                <td>{{ $clause->category ?: 'Genel' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Oluşturan:</strong></td>
                                                <td>{{ $clause->created_by ? 'User #'.$clause->created_by : 'Sistem' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Oluşturma:</strong></td>
                                                <td>{{ $clause->created_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                        </table>

                                        <hr>
                                        <h6>Madde Yazım İpuçları</h6>
                                        <ul class="small text-muted">
                                            <li><strong>Süre:</strong> Gün sayısı, başlangıç/bitiş tarihleri</li>
                                            <li><strong>Ödeme:</strong> Yüzde oranları, vade süreleri</li>
                                            <li><strong>Garanti:</strong> Garanti süresi (ay/yıl)</li>
                                            <li><strong>İSG:</strong> Mevzuat referansları (6331 sayılı kanun)</li>
                                            <li><strong>Sigorta:</strong> Poliçe türleri ve limitler</li>
                                            <li><strong>Fesih:</strong> Bildirim süreleri ve koşullar</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
