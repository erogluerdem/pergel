@extends('layouts.master')

@section('title')
    Yeni Görev Oluştur
@endsection

@section('topbar-title')
    Görev Yönetimi
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title mb-0">
                            <i class="mdi mdi-plus-circle me-2"></i>
                            Yeni Görev Oluştur
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i>
                            Geri Dön
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Görev Başlığı *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Açıklama *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Öncelik *</label>
                                <select class="form-select @error('priority') is-invalid @enderror" 
                                        id="priority" name="priority" required>
                                    <option value="">Öncelik Seçin</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Düşük</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Orta</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Yüksek</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Acil</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Atanan Kişi *</label>
                                <select class="form-select @error('assigned_to') is-invalid @enderror" 
                                        id="assigned_to" name="assigned_to" required>
                                    <option value="">Kişi Seçin</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="due_date" class="form-label">Bitiş Tarihi *</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" name="due_date" value="{{ old('due_date') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="mdi mdi-information-outline me-2"></i>
                                        Bilgilendirme
                                    </h6>
                                    <ul class="mb-0">
                                        <li>Görev oluşturulduktan sonra atanan kişiye otomatik bildirim gönderilecektir.</li>
                                        <li>Tüm alanlar zorunludur ve doğru şekilde doldurulmalıdır.</li>
                                        <li>Bitiş tarihi bugünden sonra bir tarih olmalıdır.</li>
                                        <li>Görev durumu varsayılan olarak "Bekliyor" olarak ayarlanacaktır.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-close me-1"></i>
                                    İptal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-check me-1"></i>
                                    Görev Oluştur
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date to today
    const dueDateInput = document.getElementById('due_date');
    const today = new Date().toISOString().split('T')[0];
    dueDateInput.setAttribute('min', today);
    
    // Auto-focus on title field
    document.getElementById('title').focus();
    
    // Priority color indication
    const prioritySelect = document.getElementById('priority');
    prioritySelect.addEventListener('change', function() {
        const priority = this.value;
        this.className = 'form-select';
        
        switch(priority) {
            case 'urgent':
                this.classList.add('border-danger');
                break;
            case 'high':
                this.classList.add('border-warning');
                break;
            case 'medium':
                this.classList.add('border-info');
                break;
            case 'low':
                this.classList.add('border-success');
                break;
        }
    });
});
</script>
@endsection