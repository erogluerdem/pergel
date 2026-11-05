@extends('layouts.master')

@section('topbar-title')
    <h4><i class="bx bx-plus-circle"></i> Yeni Harcama Ekle - {{ $project->name }}</h4>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Yeni Harcama Ekle</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('projects.costs.store', $project) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="villa_id">Villa (Opsiyonel)</label>
                                <select name="villa_id" id="villa_id" class="form-control">
                                    <option value="">Genel Harcama</option>
                                    @foreach($villas as $villa)
                                        <option value="{{ $villa->id }}">{{ $villa->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">Kategori *</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">Seçiniz</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_id">Tedarikçi</label>
                                <select name="supplier_id" id="supplier_id" class="form-control">
                                    <option value="">Seçiniz</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Tutar *</label>
                                <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expense_date">Tarih *</label>
                                <input type="date" name="expense_date" id="expense_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">Açıklama</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                        <a href="{{ route('projects.costs.index', $project) }}" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
