@extends('layouts.master')

@section('title', 'Yeni QR Kod Oluştur')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-qrcode"></i> Yeni QR Kod Oluştur
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('facility.qr.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="project_id" class="form-label">Proje <span class="text-danger">*</span></label>
                            <select name="project_id" id="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
                                <option value="">Proje Seçin...</option>
                                @if(isset($projects))
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="unit_id" class="form-label">Daire/Ünite <span class="text-danger">*</span></label>
                            <select name="unit_id" id="unit_id" class="form-select @error('unit_id') is-invalid @enderror" required>
                                <option value="">Daire Seçin...</option>
                                @if(isset($units))
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->unit_number }} - {{ $unit->unit_type }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('unit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="qr_label" class="form-label">Etiket (İsteğe Bağlı)</label>
                            <input type="text" name="qr_label" id="qr_label" class="form-control @error('qr_label') is-invalid @enderror" 
                                   placeholder="örn: Giriş Kapısı, Asansör, vs..." value="{{ old('qr_label') }}">
                            <small class="form-text text-muted">QR kodu tanımlamak için açıklayıcı bir etiket girin.</small>
                            @error('qr_label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-text mb-3 p-3 bg-light rounded">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Not:</strong> QR kodu oluşturduktan sonra indirip yazdırıp daire içine yapıştırabilirsiniz. 
                            Müşteriler bu kodu taradığında hızlıca destek talebini açabileceklerdir.
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('facility.qr.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> İptal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-qrcode"></i> QR Kod Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Proje seçildiğinde daireleri yükle
document.getElementById('project_id').addEventListener('change', function() {
    const projectId = this.value;
    const unitSelect = document.getElementById('unit_id');
    
    if (!projectId) {
        unitSelect.innerHTML = '<option value="">Daire Seçin...</option>';
        return;
    }

    // Burada AJAX ile daireleri yükleyebilirsiniz
    // fetch(`/api/projects/${projectId}/units`)
    //     .then(response => response.json())
    //     .then(data => {
    //         // Daireleri doldur
    //     });
});
</script>
@endpush
@endsection
