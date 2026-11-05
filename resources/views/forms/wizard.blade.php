@extends('layouts.master')

@section('title', 'Form Sihirbazı - Pergel CRM')

@section('styles')
<style>
    .wizard-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .wizard-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px 12px 0 0;
        margin-bottom: 0;
    }
    
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding: 0 1rem;
    }
    
    .step-item {
        flex: 1;
        text-align: center;
        position: relative;
    }
    
    .step-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        right: -50%;
        width: 100%;
        height: 2px;
        background: #e3e6f0;
        z-index: 1;
    }
    
    .step-item.completed::after {
        background: #1cc88a;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e3e6f0;
        color: #5a5c69;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem;
        font-weight: bold;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }
    
    .step-item.completed .step-number {
        background: #1cc88a;
        color: white;
    }
    
    .step-item.active .step-number {
        background: #4e73df;
        color: white;
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.25);
    }
    
    .step-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 0.25rem;
    }
    
    .step-item.completed .step-title,
    .step-item.active .step-title {
        color: #4e73df;
    }
    
    .template-card {
        border: 2px solid #e3e6f0;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
    }
    
    .template-card:hover {
        border-color: #4e73df;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .template-card.selected {
        border-color: #4e73df;
        background: rgba(78, 115, 223, 0.05);
    }
    
    .template-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .wizard-navigation {
        background: #f8f9fc;
        padding: 1.5rem;
        border-radius: 0 0 12px 12px;
        border-top: 1px solid #e3e6f0;
    }
    
    .form-section {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .field-group {
        margin-bottom: 1.5rem;
    }
    
    .field-label {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .progress-bar-wizard {
        height: 8px;
        background: #e3e6f0;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%);
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    
    .wizard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #4e73df;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-magic text-primary mr-2"></i>
                Form Sihirbazı
            </h1>
            <p class="text-muted mt-1">Adım adım form oluşturma ve veri girişi</p>
        </div>
        <div>
            <button class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Yeni Sihirbaz
            </button>
        </div>
    </div>

    <!-- Statistics -->
    <div class="wizard-stats">
        <div class="stat-card">
            <div class="stat-number">{{ count($formTemplates) }}</div>
            <div class="stat-label">Form Şablonu</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ count($wizardSteps) }}</div>
            <div class="stat-label">Adım Sayısı</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ collect($wizardSteps)->sum(fn($step) => count($step->fields)) }}</div>
            <div class="stat-label">Toplam Alan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">95%</div>
            <div class="stat-label">Tamamlanma Oranı</div>
        </div>
    </div>

    <div class="wizard-container">
        <!-- Wizard Card -->
        <div class="card shadow-lg border-0">
            <!-- Wizard Header -->
            <div class="wizard-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">
                            <i class="fas fa-wand-magic mr-2"></i>
                            Akıllı Form Sihirbazı
                        </h2>
                        <p class="mb-0 opacity-75">
                            Karmaşık formları kolay adımlarla doldurun ve verilerinizi organize edin
                        </p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="progress-bar-wizard">
                            <div class="progress-fill" style="width: 20%"></div>
                        </div>
                        <small class="opacity-75">Adım 1 / {{ count($wizardSteps) }}</small>
                    </div>
                </div>
            </div>

            <!-- Step Indicator -->
            <div class="card-body">
                <div class="step-indicator">
                    @foreach($wizardSteps as $index => $step)
                    <div class="step-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="step-number">
                            {{ $step->id }}
                        </div>
                        <div class="step-title">{{ $step->title }}</div>
                        <div class="text-muted small">{{ $step->description }}</div>
                    </div>
                    @endforeach
                </div>

                <!-- Current Step Content -->
                <div class="current-step-content">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="mb-3">
                                <i class="fas fa-info-circle text-primary mr-2"></i>
                                {{ $wizardSteps[0]->title }}
                            </h4>
                            <p class="text-muted mb-4">{{ $wizardSteps[0]->description }}</p>

                            <div class="form-section">
                                @foreach($wizardSteps[0]->fields as $fieldName => $fieldLabel)
                                <div class="field-group">
                                    <label class="field-label">{{ $fieldLabel }}</label>
                                    @switch($fieldName)
                                        @case('name')
                                            <input type="text" class="form-control" placeholder="Proje veya görev adını girin">
                                            @break
                                        @case('description')
                                            <textarea class="form-control" rows="3" placeholder="Detaylı açıklama yazın"></textarea>
                                            @break
                                        @case('category')
                                            <select class="form-control">
                                                <option>Kategori seçin</option>
                                                <option>Web Geliştirme</option>
                                                <option>Mobil Uygulama</option>
                                                <option>Tasarım</option>
                                                <option>Pazarlama</option>
                                                <option>Danışmanlık</option>
                                            </select>
                                            @break
                                        @case('priority')
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-outline-success">
                                                    <input type="radio" name="priority" value="low"> Düşük
                                                </label>
                                                <label class="btn btn-outline-warning active">
                                                    <input type="radio" name="priority" value="medium" checked> Orta
                                                </label>
                                                <label class="btn btn-outline-danger">
                                                    <input type="radio" name="priority" value="high"> Yüksek
                                                </label>
                                            </div>
                                            @break
                                        @default
                                            <input type="text" class="form-control" placeholder="{{ $fieldLabel }}">
                                    @endswitch
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-lightbulb mr-2"></i>
                                        İpuçları
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled small">
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success mr-2"></i>
                                            Açık ve net başlık seçin
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success mr-2"></i>
                                            Detaylı açıklama ekleyin
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success mr-2"></i>
                                            Doğru kategori belirleyin
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success mr-2"></i>
                                            Öncelik seviyesini değerlendirin
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clock mr-2"></i>
                                        İlerleme Durumu
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @foreach($wizardSteps as $index => $step)
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="mr-3">
                                            @if($index == 0)
                                                <i class="fas fa-play text-primary"></i>
                                            @else
                                                <i class="fas fa-clock text-muted"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="small font-weight-bold">{{ $step->title }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ count($step->fields) }} alan</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="wizard-navigation">
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-secondary" disabled>
                        <i class="fas fa-arrow-left mr-2"></i>
                        Önceki Adım
                    </button>
                    
                    <div class="d-flex align-items-center">
                        <span class="text-muted mr-3">Adım 1 / {{ count($wizardSteps) }}</span>
                        <button class="btn btn-outline-secondary mr-2">
                            <i class="fas fa-save mr-2"></i>
                            Taslak Kaydet
                        </button>
                        <button class="btn btn-primary">
                            Sonraki Adım
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Templates -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-templates mr-2"></i>
                            Hazır Form Şablonları
                        </h5>
                        <small class="text-muted">Hızlı başlangıç için önceden hazırlanmış şablonları kullanın</small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($formTemplates as $template)
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="template-card">
                                    <div class="template-icon">
                                        <i class="{{ $template->icon }}"></i>
                                    </div>
                                    <h6 class="mb-2">{{ $template->name }}</h6>
                                    <p class="text-muted small mb-3">{{ $template->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $template->estimated_time }}
                                        </small>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-play mr-1"></i>
                                            Başlat
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = {{ count($wizardSteps) }};
    
    // Template selection
    document.querySelectorAll('.template-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
    
    // Step navigation
    function updateProgress() {
        const percentage = (currentStep / totalSteps) * 100;
        document.querySelector('.progress-fill').style.width = percentage + '%';
        
        // Update step indicators
        document.querySelectorAll('.step-item').forEach((item, index) => {
            item.classList.remove('active', 'completed');
            if (index + 1 < currentStep) {
                item.classList.add('completed');
            } else if (index + 1 === currentStep) {
                item.classList.add('active');
            }
        });
    }
    
    // Next step button
    document.querySelector('.btn-primary[onclick*="next"]')?.addEventListener('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            updateProgress();
            
            // Show next step content (you can implement step content switching here)
            console.log('Moving to step:', currentStep);
        }
    });
    
    // Previous step button
    document.querySelector('.btn-secondary[onclick*="previous"]')?.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            updateProgress();
            
            // Show previous step content
            console.log('Moving to step:', currentStep);
        }
    });
    
    // Form validation
    function validateCurrentStep() {
        const currentStepFields = document.querySelectorAll('.form-section input, .form-section select, .form-section textarea');
        let isValid = true;
        
        currentStepFields.forEach(field => {
            if (field.hasAttribute('required') && !field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        return isValid;
    }
    
    // Auto-save functionality
    let autoSaveTimer;
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                // Implement auto-save logic here
                console.log('Auto-saving form data...');
            }, 2000);
        });
    });
    
    // Form submission
    document.querySelector('.btn-success')?.addEventListener('click', function() {
        if (validateCurrentStep()) {
            // Implement form submission logic
            alert('Form başarıyla gönderildi!');
        }
    });
});
</script>
@endsection