@extends('layouts.master')

@section('title', 'Dokümantasyon - Pergel CRM')

@section('styles')
<style>
    .documentation-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        border-radius: 12px;
    }
    
    .doc-section-card {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
    }
    
    .doc-section-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border-color: #4e73df;
    }
    
    .doc-section-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .doc-section-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }
    
    .doc-icon {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }
    
    .article-list {
        padding: 1.5rem;
    }
    
    .article-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f3f4;
        transition: all 0.2s ease;
        cursor: pointer;
        border-radius: 6px;
        margin-bottom: 0.5rem;
        padding-left: 0.75rem;
    }
    
    .article-item:hover {
        background: #f8f9fc;
        color: #4e73df;
        transform: translateX(5px);
    }
    
    .article-item:last-child {
        border-bottom: none;
    }
    
    .quick-link-card {
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }
    
    .quick-link-card:hover {
        background: #4e73df;
        color: white;
        text-decoration: none;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
    }
    
    .quick-link-icon {
        width: 50px;
        height: 50px;
        background: #e3f2fd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.25rem;
        color: #1976d2;
        transition: all 0.3s ease;
    }
    
    .quick-link-card:hover .quick-link-icon {
        background: rgba(255,255,255,0.2);
        color: white;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid #4e73df;
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
    
    .recent-update {
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .recent-update:hover {
        background: #f8f9fc;
        border-color: #4e73df;
    }
    
    .update-type {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .update-type.feature {
        background: #d4edda;
        color: #155724;
    }
    
    .update-type.update {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .update-type.improvement {
        background: #fff3cd;
        color: #856404;
    }
    
    .search-box {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .search-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e3e6f0;
        border-radius: 50px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
        outline: none;
    }
    
    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 1.1rem;
    }
    
    .breadcrumb-custom {
        background: transparent;
        padding: 0;
        margin-bottom: 1rem;
    }
    
    .breadcrumb-custom .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        font-size: 1.2rem;
        color: #4e73df;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Documentation Header -->
    <div class="documentation-header text-center">
        <div class="container">
            <h1 class="display-4 mb-3">
                <i class="fas fa-book mr-3"></i>
                Pergel CRM Dokümantasyonu
            </h1>
            <p class="lead mb-4">
                Sistemin tüm özelliklerini öğrenmek ve etkili kullanım için kapsamlı rehber
            </p>
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Dokümantasyonda ara...">
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ count($sections) }}</div>
            <div class="stat-label">Ana Bölüm</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ collect($sections)->sum(fn($section) => count($section->articles)) }}</div>
            <div class="stat-label">Makale</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ count($quickLinks) }}</div>
            <div class="stat-label">Hızlı Link</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ count($recentUpdates) }}</div>
            <div class="stat-label">Son Güncelleme</div>
        </div>
    </div>

    <div class="row">
        <!-- Main Documentation Sections -->
        <div class="col-lg-8">
            <div class="row">
                @foreach($sections as $section)
                <div class="col-md-6 mb-4">
                    <div class="card doc-section-card">
                        <div class="doc-section-header">
                            <div class="doc-icon">
                                <i class="{{ $section->icon }}"></i>
                            </div>
                            <h5 class="mb-1">{{ $section->title }}</h5>
                            <p class="mb-0 opacity-75">{{ $section->description }}</p>
                        </div>
                        <div class="article-list">
                            @foreach($section->articles as $article)
                            <div class="article-item">
                                <i class="fas fa-file-alt mr-2 text-muted"></i>
                                {{ $article }}
                            </div>
                            @endforeach
                            <div class="mt-3">
                                <a href="/help/section/{{ $section->id }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-arrow-right mr-1"></i>
                                    Tümünü Görüntüle
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Links -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>
                        Hızlı Erişim
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($quickLinks as $link)
                        <div class="col-6 mb-3">
                            <a href="{{ $link->url }}" class="quick-link-card">
                                <div class="quick-link-icon">
                                    <i class="{{ $link->icon }}"></i>
                                </div>
                                <h6 class="mb-0">{{ $link->title }}</h6>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Updates -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>
                        Son Güncellemeler
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($recentUpdates as $update)
                    <div class="recent-update">
                        <span class="update-type {{ $update->type }}">{{ ucfirst($update->type) }}</span>
                        <h6 class="mb-1">{{ $update->title }}</h6>
                        <small class="text-muted">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ \Carbon\Carbon::parse($update->date)->format('d.m.Y') }}
                        </small>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Help & Support -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-life-ring mr-2"></i>
                        Yardım & Destek
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h6>Daha fazla yardıma mı ihtiyacınız var?</h6>
                        <p class="text-muted small mb-3">
                            Teknik destek ekibimiz size yardımcı olmaya hazır.
                        </p>
                        <a href="/support/ticket" class="btn btn-primary btn-sm">
                            <i class="fas fa-ticket-alt mr-2"></i>
                            Destek Talebi Oluştur
                        </a>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-phone mr-1"></i>
                                +90 (212) 555-0123
                            </small><br>
                            <small class="text-muted">
                                <i class="fas fa-envelope mr-1"></i>
                                destek@pergel.com
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Articles -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-star text-warning mr-2"></i>
                        Popüler Makaleler
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="font-weight-bold">1</span>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">Sisteme İlk Giriş</h6>
                                    <small class="text-muted">1,245 görüntüleme</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="font-weight-bold">2</span>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">Proje Oluşturma</h6>
                                    <small class="text-muted">987 görüntüleme</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="font-weight-bold">3</span>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">Fatura İşlemleri</h6>
                                    <small class="text-muted">756 görüntüleme</small>
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
    // Search functionality
    const searchInput = document.querySelector('.search-input');
    const sections = document.querySelectorAll('.doc-section-card');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        sections.forEach(section => {
            const title = section.querySelector('h5').textContent.toLowerCase();
            const description = section.querySelector('p').textContent.toLowerCase();
            const articles = Array.from(section.querySelectorAll('.article-item')).map(item => 
                item.textContent.toLowerCase()
            ).join(' ');
            
            const shouldShow = title.includes(searchTerm) || 
                             description.includes(searchTerm) || 
                             articles.includes(searchTerm);
            
            section.closest('.col-md-6').style.display = shouldShow ? 'block' : 'none';
        });
    });
    
    // Article item click handlers
    document.querySelectorAll('.article-item').forEach(item => {
        item.addEventListener('click', function() {
            const articleTitle = this.textContent.trim();
            console.log('Article clicked:', articleTitle);
            // Here you can implement navigation to specific article
            // window.location.href = `/help/article/${encodeURIComponent(articleTitle)}`;
        });
    });
    
    // Section card hover effects
    document.querySelectorAll('.doc-section-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Quick link tracking
    document.querySelectorAll('.quick-link-card').forEach(link => {
        link.addEventListener('click', function(e) {
            const title = this.querySelector('h6').textContent;
            console.log('Quick link clicked:', title);
            // Here you can implement analytics tracking
        });
    });
    
    // Recent updates click handlers
    document.querySelectorAll('.recent-update').forEach(update => {
        update.addEventListener('click', function() {
            const title = this.querySelector('h6').textContent;
            console.log('Update clicked:', title);
            // Here you can implement navigation to update details
        });
    });
    
    // Popular articles click handlers
    document.querySelectorAll('.popular-article').forEach(article => {
        article.addEventListener('click', function() {
            const title = this.querySelector('h6').textContent;
            console.log('Popular article clicked:', title);
            // Here you can implement navigation to article
        });
    });
});
</script>
@endsection