@extends('layouts.master')

@section('title', 'Risk Düzenle')

@section('content')
    @php
        $projects = \App\Models\Project::where('deleted_at', null)->get();
    @endphp
    
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Risk Düzenle</h2>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('finance.risks.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Geri Dön</a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="POST" action="{{ route('finance.risks.update', $risk) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="form-label">Proje <span class="text-danger">*</span></label>
                                <select name="project_id" class="form-select" required>
                                    <option value="">Seçiniz</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ $risk->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Risk Türü <span class="text-danger">*</span></label>
                                <select name="risk_type" class="form-select" required>
                                    <option value="overdue" {{ $risk->risk_type == 'overdue' ? 'selected' : '' }}>Ödemeler Gecikmiş</option>
                                    <option value="cash_shortage" {{ $risk->risk_type == 'cash_shortage' ? 'selected' : '' }}>Nakit Sıkıntısı</option>
                                    <option value="currency_fluctuation" {{ $risk->risk_type == 'currency_fluctuation' ? 'selected' : '' }}>Döviz Dalgalanması</option>
                                    <option value="vendor_default" {{ $risk->risk_type == 'vendor_default' ? 'selected' : '' }}>Tedarikçi Ödeme Yapamama</option>
                                    <option value="budget_overrun" {{ $risk->risk_type == 'budget_overrun' ? 'selected' : '' }}>Bütçe Aşımı</option>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Olasılık (%) <span class="text-danger">*</span></label>
                                    <input type="number" name="probability" class="form-control" min="0" max="100" step="0.01" value="{{ $risk->probability }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Etki (%) <span class="text-danger">*</span></label>
                                    <input type="number" name="impact" class="form-control" min="0" max="100" step="0.01" value="{{ $risk->impact }}" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Açıklama</label>
                                <textarea name="description" class="form-control" rows="3">{{ $risk->description }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Azaltma Stratejileri</label>
                                <textarea name="mitigation_strategy" class="form-control" rows="3">{{ $risk->mitigation_strategy }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Durum</label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ $risk->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="resolved" {{ $risk->status == 'resolved' ? 'selected' : '' }}>Çözüldü</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Güncelle</button>
                            <a href="{{ route('finance.risks.index') }}" class="btn btn-secondary">İptal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
