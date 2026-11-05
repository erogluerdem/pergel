@extends('layouts.master')

@section('title','Şablon Düzenle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Şablon Düzenle: {{ $template->title }}</h5>
                        <a href="{{ route('contracts.library.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('contracts.library.templates.update', $template->id) }}">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Şablon Adı <span class="text-danger">*</span></label>
                                    <input name="title" class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title', $template->title) }}" required>
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Şablon İçeriği <span class="text-danger">*</span></label>
                                    <textarea name="body" class="form-control @error('body') is-invalid @enderror"
                                              rows="20" required>{{ old('body', $template->body) }}</textarea>
                                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Şablon maddelerini düzenleyebilirsiniz. Her satır yeni bir paragraf olacaktır.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Durum</label>
                                    <select name="status" class="form-select">
                                        <option value="draft" {{ $template->status === 'draft' ? 'selected' : '' }}>Taslak</option>
                                        <option value="active" {{ $template->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                    </select>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save"></i> Güncelle
                                    </button>
                                    <a href="{{ route('contracts.library.index') }}" class="btn btn-secondary">İptal</a>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Şablon Bilgileri</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>ID:</strong></td>
                                                <td>{{ $template->id }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Oluşturan:</strong></td>
                                                <td>{{ $template->created_by ? 'User #'.$template->created_by : 'Sistem' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Oluşturma:</strong></td>
                                                <td>{{ $template->created_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Güncelleme:</strong></td>
                                                <td>{{ $template->updated_at->format('d.m.Y H:i') }}</td>
                                            </tr>
                                        </table>

                                        <hr>
                                        <h6>Öneriler</h6>
                                        <ul class="small text-muted">
                                            <li>Madde numaralarını (1., 2., 3.) kullanın</li>
                                            <li>Taraf tanımlarını açık yazın</li>
                                            <li>Ödeme koşullarını detaylandırın</li>
                                            <li>Süre ve cezai şartları belirtin</li>
                                            <li>Mücbir sebep maddesi ekleyin</li>
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
