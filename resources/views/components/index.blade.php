@extends('layouts.master')

@section('title', 'UI Bileşenleri - Pergel CRM')

@section('styles')
<style>
    .component-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.3s ease;
        border: 1px solid #e3e6f0;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .component-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border-color: #4e73df;
    }
    
    .component-preview {
        height: 180px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .component-preview::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }
    
    .component-icon {
        font-size: 3rem;
        color: white;
        opacity: 0.9;
        z-index: 1;
        position: relative;
    }
    
    .category-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 1rem;
        margin: -1rem -1rem 1.5rem -1rem;
        border-radius: 8px 8px 0 0;
    }
    
    .usage-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
        margin-top: 0.5rem;
    }
    
    .usage-tag {
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .stats-row {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .stat-item {
        text-align: center;
        padding: 1.5rem;
        border-right: 1px solid #e3e6f0;
    }
    
    .stat-item:last-child {
        border-right: none;
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
                <i class="fas fa-puzzle-piece text-primary mr-2"></i>
                UI Bileşenleri
            </h1>
            <p class="text-muted mt-1">Yeniden kullanılabilir arayüz bileşenleri ve örnekleri</p>
        </div>
        <div>
            <button class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Yeni Bileşen
            </button>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row stats-row">
        <div class="col-md-3 stat-item">
            <div class="stat-number">{{ collect($components)->sum(fn($cat) => count($cat['items'])) }}</div>
            <div class="stat-label">Toplam Bileşen</div>
        </div>
        <div class="col-md-3 stat-item">
            <div class="stat-number">{{ count($components) }}</div>
            <div class="stat-label">Kategori</div>
        </div>
        <div class="col-md-3 stat-item">
            <div class="stat-number">{{ collect($components)->flatten(1)->where('usage', 'like', '%forms%')->count() }}</div>
            <div class="stat-label">Form Bileşeni</div>
        </div>
        <div class="col-md-3 stat-item">
            <div class="stat-number">{{ collect($components)->flatten(1)->where('usage', 'like', '%analytics%')->count() }}</div>
            <div class="stat-label">Analitik Bileşeni</div>
        </div>
    </div>

    <!-- Components Grid -->
    @foreach($components as $category)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="category-header">
                    <h4 class="mb-0">
                        <i class="fas fa-layer-group mr-2"></i>
                        {{ $category['category'] }}
                    </h4>
                    <small class="opacity-75">{{ count($category['items']) }} bileşen</small>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        @foreach($category['items'] as $component)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card component-card h-100">
                                <div class="component-preview">
                                    @switch(true)
                                        @case(str_contains($component->name, 'Tarih'))
                                            <i class="fas fa-calendar-alt component-icon"></i>
                                            @break
                                        @case(str_contains($component->name, 'Dropdown') || str_contains($component->name, 'Seçim'))
                                            <i class="fas fa-list component-icon"></i>
                                            @break
                                        @case(str_contains($component->name, 'Dosya'))
                                            <i class="fas fa-cloud-upload-alt component-icon"></i>
                                            @break
                                        @case(str_contains($component->name, 'Pasta'))
                                            <i class="fas fa-chart-pie component-icon"></i>
                                            @break
                                        @case(str_contains($component->name, 'Çubuk'))
                                            <i class="fas fa-chart-bar component-icon"></i>
                                            @break
                                        @case(str_contains($component->name, 'Zaman'))
                                            <i class="fas fa-project-diagram component-icon"></i>
                                            @break
                                        @case(str_contains($component->name, 'Breadcrumb'))
                                            <i class="fas fa-sitemap component-icon"></i>
                                            @break
                                        @case(str_contains($component->name, 'Sidebar'))
                                            <i class="fas fa-bars component-icon"></i>
                                            @break
                                        @case(str_contains($component->name, 'Tab'))
                                            <i class="fas fa-folder component-icon"></i>
                                            @break
                                        @case(str_contains($component->name, 'Toast'))
                                            <i class="fas fa-bell component-icon"></i>
                                            @break
                                        @case(str_contains($component->name, 'Modal'))
                                            <i class="fas fa-window-maximize component-icon"></i>
                                            @break
                                        @case(str_contains($component->name, 'İlerleme'))
                                            <i class="fas fa-tasks component-icon"></i>
                                            @break
                                        @default
                                            <i class="fas fa-puzzle-piece component-icon"></i>
                                    @endswitch
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title mb-2">{{ $component->name }}</h5>
                                    <p class="card-text text-muted small mb-3">{{ $component->description }}</p>
                                    
                                    <div class="usage-tags">
                                        @foreach(explode(', ', $component->usage) as $tag)
                                        <span class="usage-tag">{{ trim($tag) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-transparent">
                                    <div class="btn-group w-100">
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye mr-1"></i>
                                            Önizle
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-code mr-1"></i>
                                            Kod
                                        </button>
                                        <button class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-download mr-1"></i>
                                            İndir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    
    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tools mr-2"></i>
                        Geliştirici Araçları
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light border-left-primary h-100">
                                <div class="card-body py-3">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Stil Kılavuzu
                                    </div>
                                    <div class="text-dark">Tasarım sistemi ve renkler</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light border-left-success h-100">
                                <div class="card-body py-3">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Dokümantasyon
                                    </div>
                                    <div class="text-dark">API ve kullanım örnekleri</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light border-left-info h-100">
                                <div class="card-body py-3">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Test Alanı
                                    </div>
                                    <div class="text-dark">Bileşenleri test et</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light border-left-warning h-100">
                                <div class="card-body py-3">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Tema Editörü
                                    </div>
                                    <div class="text-dark">CSS değişkenleri</div>
                                </div>
                            </div>
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
    // Component card hover effects
    const componentCards = document.querySelectorAll('.component-card');
    
    componentCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Button interactions
    document.querySelectorAll('[data-action="preview"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const componentName = this.closest('.component-card').querySelector('.card-title').textContent;
            alert(`${componentName} önizlemesi açılacak...`);
        });
    });
    
    document.querySelectorAll('[data-action="code"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const componentName = this.closest('.component-card').querySelector('.card-title').textContent;
            alert(`${componentName} için kod örneği gösterilecek...`);
        });
    });
    
    document.querySelectorAll('[data-action="download"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const componentName = this.closest('.component-card').querySelector('.card-title').textContent;
            alert(`${componentName} indirilecek...`);
        });
    });
});
</script>
@endsection