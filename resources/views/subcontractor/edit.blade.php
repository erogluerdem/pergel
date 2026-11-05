@extends('layouts.master')

@section('title', 'Taşeron Düzenle - ' . ($subcontractor->company_name ?? ''))

@section('topbar-title', 'Taşeron Yönetimi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Taşeron Düzenle</h4>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('subcontractor.update', $subcontractor->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Firma Adı</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $subcontractor->company_name) }}" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Uzmanlık Alanı</label>
                            <input type="text" name="expertise_area" class="form-control" value="{{ old('expertise_area', $subcontractor->expertise_area) }}" />
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Performans Skoru (0-100)</label>
                                <input type="number" step="0.1" min="0" max="100" name="performance_score" class="form-control" value="{{ old('performance_score', $subcontractor->performance_score) }}" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">İSG Skoru (0-100)</label>
                                <input type="number" step="0.1" min="0" max="100" name="safety_score" class="form-control" value="{{ old('safety_score', $subcontractor->safety_score) }}" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Logo (opsiyonel)</label>
                            @if(!empty($subcontractor->logo))
                                <div class="mb-2">
                                    <img src="{{ asset($subcontractor->logo) }}" alt="logo" style="height:64px; object-fit:cover;" />
                                </div>
                            @endif
                            <input type="file" name="logo" accept="image/*" class="form-control" />
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefon</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $subcontractor->phone) }}" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">E-posta</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $subcontractor->email) }}" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Adres</label>
                            <textarea name="address" class="form-control" rows="3">{{ old('address', $subcontractor->address) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Durum</label>
                            <select name="status" class="form-select">
                                <option value="approved" {{ old('status', $subcontractor->status) == 'approved' ? 'selected' : '' }}>Onaylı</option>
                                <option value="pending" {{ old('status', $subcontractor->status) == 'pending' ? 'selected' : '' }}>Onay Bekliyor</option>
                                <option value="conditional" {{ old('status', $subcontractor->status) == 'conditional' ? 'selected' : '' }}>Şartlı Onaylı</option>
                                <option value="blacklisted" {{ old('status', $subcontractor->status) == 'blacklisted' ? 'selected' : '' }}>Kara Listede</option>
                                <option value="inactive" {{ old('status', $subcontractor->status) == 'inactive' ? 'selected' : '' }}>Pasif</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('subcontractor.companies') }}" class="btn btn-light">Vazgeç</a>
                            <button class="btn btn-primary" type="submit">Güncelle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
