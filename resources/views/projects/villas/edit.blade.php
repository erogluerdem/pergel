@extends('layouts.master')

@section('topbar-title')
    <h4><i class="bx bx-edit"></i> Villa Düzenle - {{ $villa->name }}</h4>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Villa Bilgilerini Düzenle</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('projects.villas.update', [$project, $villa]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Villa Adı *</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $villa->name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="target_cost">Hedef Maliyet (TL) *</label>
                                <input type="number" name="target_cost" id="target_cost" class="form-control" step="0.01" value="{{ $villa->target_cost }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Durum *</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="planned" {{ $villa->status == 'planned' ? 'selected' : '' }}>Planlandı</option>
                            <option value="in_progress" {{ $villa->status == 'in_progress' ? 'selected' : '' }}>Devam Ediyor</option>
                            <option value="completed" {{ $villa->status == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                        <a href="{{ route('projects.costs.index', $project) }}" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
