@extends('layouts.master')

@section('title')
    Görev Düzenle
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
                            <i class="mdi mdi-pencil-circle me-2"></i>
                            Görev Düzenle
                        </h4>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex gap-2">
                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info">
                                <i class="mdi mdi-eye me-1"></i>
                                Detay
                            </a>
                            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i>
                                Geri Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Görev Başlığı *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $task->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Açıklama *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" required>{{ old('description', $task->description) }}</textarea>
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
                                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Düşük</option>
                                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Orta</option>
                                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>Yüksek</option>
                                    <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>Acil</option>
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
                                        <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Durum *</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="">Durum Seçin</option>
                                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Bekliyor</option>
                                    <option value="active" {{ old('status', $task->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="review" {{ old('status', $task->status) == 'review' ? 'selected' : '' }}>İnceleme</option>
                                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                    <option value="cancelled" {{ old('status', $task->status) == 'cancelled' ? 'selected' : '' }}>İptal</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="due_date" class="form-label">Bitiş Tarihi *</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" name="due_date" 
                                       value="{{ old('due_date', $task->due_date->format('Y-m-d')) }}" 
                                       required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="completion_rate" class="form-label">
                                    Tamamlanma Oranı (%)
                                    <span id="completion-value">{{ old('completion_rate', $task->completion_rate ?? 0) }}%</span>
                                </label>
                                <input type="range" class="form-range @error('completion_rate') is-invalid @enderror" 
                                       id="completion_rate" name="completion_rate" 
                                       min="0" max="100" step="5"
                                       value="{{ old('completion_rate', $task->completion_rate ?? 0) }}">
                                @error('completion_rate')
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
                                        Görev Bilgileri
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-muted">Oluşturan:</small>
                                            <div>{{ $task->createdByUser->name ?? 'Bilinmiyor' }}</div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Oluşturulma Tarihi:</small>
                                            <div>{{ $task->created_at->format('d.m.Y H:i') }}</div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Son Güncellenme:</small>
                                            <div>{{ $task->updated_at->format('d.m.Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-secondary">
                                    <i class="mdi mdi-close me-1"></i>
                                    İptal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-check me-1"></i>
                                    Değişiklikleri Kaydet
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
    // Completion rate slider
    const completionSlider = document.getElementById('completion_rate');
    const completionValue = document.getElementById('completion-value');
    
    completionSlider.addEventListener('input', function() {
        completionValue.textContent = this.value + '%';
    });

    // Status change auto-completion
    const statusSelect = document.getElementById('status');
    statusSelect.addEventListener('change', function() {
        if (this.value === 'completed') {
            completionSlider.value = 100;
            completionValue.textContent = '100%';
        } else if (this.value === 'pending') {
            completionSlider.value = 0;
            completionValue.textContent = '0%';
        }
    });

    // Priority color indication
    const prioritySelect = document.getElementById('priority');
    function updatePriorityColor() {
        const priority = prioritySelect.value;
        prioritySelect.className = 'form-select';
        
        switch(priority) {
            case 'urgent':
                prioritySelect.classList.add('border-danger');
                break;
            case 'high':
                prioritySelect.classList.add('border-warning');
                break;
            case 'medium':
                prioritySelect.classList.add('border-info');
                break;
            case 'low':
                prioritySelect.classList.add('border-success');
                break;
        }
    }
    
    updatePriorityColor(); // Set initial color
    prioritySelect.addEventListener('change', updatePriorityColor);
});
</script>
@endsection