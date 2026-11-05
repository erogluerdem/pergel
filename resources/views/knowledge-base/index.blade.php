@extends('layouts.master')

@section('title', 'Bilgi Bankası - Pergel CRM')

@section('styles')
<style>
    .knowledge-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }
    
    .knowledge-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }
    
    .knowledge-header .container {
        position: relative;
        z-index: 1;
    }
    
    .category-card {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
        cursor: pointer;
    }
    
    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border-color: transparent;
    }
    
    .category-header {
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .category-header.primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
    
    .category-header.success {
        background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%);
    }
    
    .category-header.warning {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    }
    
    .category-header.info {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }
    
    .category-header.danger {
        background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%);
    }
    
    .category-icon {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-bottom: 1rem;
    }
    
    .category-body {
        padding: 1.5rem;
        background: white;
    }
    
    .article-preview {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f3f4;
        transition: all 0.2s ease;
    }
    
    .article-preview:hover {
        background: #f8f9fc;
        margin: 0 -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 6px;
    }
    
    .article-preview:last-child {
        border-bottom: none;
    }
    
    .article-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 0.5rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    .meta-item i {
        margin-right: 0.25rem;
    }
    
    .popular-article-card {
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .popular-article-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #4e73df;
    }
    
    .popular-rank {
        position: absolute;
        top: 1rem;
        left: 1rem;
        width: 30px;
        height: 30px;
        background: #4e73df;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.875rem;
    }
    
    .popular-content {
        margin-left: 50px;
    }
    
    .helpful-score {
        display: inline-block;
        background: #28a745;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .search-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-left: 4px solid #4e73df;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .stat-number {
        font-size: 2.5rem;
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
    
    .recent-article {
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }
    
    .recent-article:hover {
        background: #f8f9fc;
        border-color: #4e73df;
    }
    
    .recent-date {
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .floating-action {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 60px;
        height: 60px;
        background: #4e73df;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
        transition: all 0.3s ease;
        cursor: pointer;
        z-index: 1000;
    }
    
    .floating-action:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.5);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Knowledge Base Header -->
    <div class="knowledge-header text-center">
        <div class="container">
            <h1 class="display-4 mb-3">
                <i class="fas fa-graduation-cap mr-3"></i>
                Bilgi Bankası
            </h1>
            <p class="lead mb-4">
                İhtiyacınız olan tüm bilgilere hızla ulaşın ve sistemi etkili kullanmayı öğrenin
            </p>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Bilgi bankasında ara... (örn: proje oluşturma)">
                </div>
            </div>
            <div class="col-md-4 text-right">
                <button class="btn btn-primary">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrele
                </button>
                <button class="btn btn-outline-secondary ml-2">
                    <i class="fas fa-sort mr-2"></i>
                    Sırala
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_articles'] }}</div>
            <div class="stat-label">Toplam Makale</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['total_views']) }}</div>
            <div class="stat-label">Toplam Görüntüleme</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['avg_helpful'] }}%</div>
            <div class="stat-label">Yardımcı Oranı</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['categories'] }}</div>
            <div class="stat-label">Kategori</div>
        </div>
    </div>

    <div class="row">
        <!-- Categories -->
        <div class="col-lg-8">
            <h4 class="mb-4">
                <i class="fas fa-th-large text-primary mr-2"></i>
                Kategoriler
            </h4>
            <div class="row">
                @foreach($categories as $category)
                <div class="col-md-6 mb-4">
                    <div class="card category-card">
                        <div class="category-header {{ $category->color }}">
                            <div class="category-icon">
                                <i class="{{ $category->icon }}"></i>
                            </div>
                            <h5 class="text-white mb-1">{{ $category->name }}</h5>
                            <p class="text-white-50 mb-0 small">{{ $category->description }}</p>
                            <div class="text-right mt-2">
                                <span class="badge badge-light">{{ $category->article_count }} makale</span>
                            </div>
                        </div>
                        <div class="category-body">
                            @foreach($category->articles as $article)
                            <div class="article-preview">
                                <h6 class="mb-1">{{ $article->title }}</h6>
                                <div class="article-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-eye"></i>
                                        {{ number_format($article->views) }}
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-thumbs-up"></i>
                                        {{ $article->helpful }}%
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="text-center mt-3">
                                <a href="/knowledge-base/category/{{ $category->id }}" class="btn btn-sm btn-outline-{{ $category->color }}">
                                    <i class="fas fa-arrow-right mr-1"></i>
                                    Tümünü Gör
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
            <!-- Popular Articles -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-fire mr-2"></i>
                        Popüler Makaleler
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($popularArticles as $index => $article)
                    <div class="popular-article-card">
                        <div class="popular-rank">{{ $index + 1 }}</div>
                        <div class="popular-content">
                            <h6 class="mb-1">{{ $article->title }}</h6>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ $article->category }}</small>
                                <span class="helpful-score">{{ $article->helpful }}% yararlı</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted small">
                                <span><i class="fas fa-eye mr-1"></i>{{ number_format($article->views) }}</span>
                                <span><i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($article->updated)->format('d.m.Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Articles -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock mr-2"></i>
                        Son Eklenen Makaleler
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($recentArticles as $article)
                    <div class="recent-article">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-1">{{ $article->title }}</h6>
                            <span class="recent-date">YENİ</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>{{ $article->category }}</span>
                            <span>{{ $article->author }}</span>
                        </div>
                        <div class="text-muted small mt-1">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ \Carbon\Carbon::parse($article->created)->format('d.m.Y') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>
                        Hızlı İşlemler
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="/help/faq" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-question-circle text-primary mr-2"></i>
                                <strong>SSS</strong>
                                <div class="small text-muted">Sık sorulan sorular</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        <a href="/help/videos" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-video text-success mr-2"></i>
                                <strong>Video Eğitimler</strong>
                                <div class="small text-muted">Adım adım rehberler</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        <a href="/support/ticket" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-headset text-info mr-2"></i>
                                <strong>Destek Talebi</strong>
                                <div class="small text-muted">Teknik yardım alın</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        <a href="/api/documentation" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-code text-warning mr-2"></i>
                                <strong>API Dokümantasyonu</strong>
                                <div class="small text-muted">Geliştirici rehberi</div>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-4">
                    <h5 class="mb-3">
                        <i class="fas fa-heart text-danger mr-2"></i>
                        Aradığınızı bulamadınız mı?
                    </h5>
                    <p class="text-muted mb-4">
                        Bizimle iletişime geçin, size yardımcı olmaktan mutluluk duyarız.
                    </p>
                    <div class="btn-group">
                        <a href="/support/ticket" class="btn btn-primary">
                            <i class="fas fa-ticket-alt mr-2"></i>
                            Destek Talebi Oluştur
                        </a>
                        <a href="/help/contact" class="btn btn-outline-secondary">
                            <i class="fas fa-envelope mr-2"></i>
                            Bize Yazın
                        </a>
                        <a href="/help/suggestion" class="btn btn-outline-success">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Öneri Gönderin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<div class="floating-action" title="Yardım Al">
    <i class="fas fa-question"></i>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.querySelector('.search-input');
    const categoryCards = document.querySelectorAll('.category-card');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        categoryCards.forEach(card => {
            const categoryName = card.querySelector('h5').textContent.toLowerCase();
            const categoryDescription = card.querySelector('p').textContent.toLowerCase();
            const articles = Array.from(card.querySelectorAll('.article-preview h6')).map(h6 => 
                h6.textContent.toLowerCase()
            ).join(' ');
            
            const shouldShow = categoryName.includes(searchTerm) || 
                             categoryDescription.includes(searchTerm) || 
                             articles.includes(searchTerm);
            
            card.closest('.col-md-6').style.display = shouldShow ? 'block' : 'none';
        });
    });
    
    // Category card click handlers
    categoryCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('a')) {
                const categoryId = this.querySelector('a').href.split('/').pop();
                window.location.href = `/knowledge-base/category/${categoryId}`;
            }
        });
    });
    
    // Article preview click handlers
    document.querySelectorAll('.article-preview').forEach(preview => {
        preview.addEventListener('click', function() {
            const title = this.querySelector('h6').textContent;
            console.log('Article clicked:', title);
            // Here you can implement navigation to specific article
        });
    });
    
    // Popular article click handlers
    document.querySelectorAll('.popular-article-card').forEach(card => {
        card.addEventListener('click', function() {
            const title = this.querySelector('h6').textContent;
            console.log('Popular article clicked:', title);
            // Here you can implement navigation to article
        });
    });
    
    // Recent article click handlers
    document.querySelectorAll('.recent-article').forEach(article => {
        article.addEventListener('click', function() {
            const title = this.querySelector('h6').textContent;
            console.log('Recent article clicked:', title);
            // Here you can implement navigation to article
        });
    });
    
    // Floating action button
    document.querySelector('.floating-action').addEventListener('click', function() {
        // Open help modal or redirect to help page
        window.location.href = '/help';
    });
    
    // Stat card animations
    function animateStats() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach(stat => {
            const finalValue = parseInt(stat.textContent.replace(/,/g, ''));
            const duration = 2000;
            const increment = finalValue / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= finalValue) {
                    current = finalValue;
                    clearInterval(timer);
                }
                
                if (stat.textContent.includes('%')) {
                    stat.textContent = Math.floor(current) + '%';
                } else if (finalValue > 1000) {
                    stat.textContent = Math.floor(current).toLocaleString();
                } else {
                    stat.textContent = Math.floor(current);
                }
            }, 16);
        });
    }
    
    // Trigger stat animation when in viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats();
                observer.unobserve(entry.target);
            }
        });
    });
    
    observer.observe(document.querySelector('.stats-grid'));
    
    // Helpful score hover effects
    document.querySelectorAll('.helpful-score').forEach(score => {
        score.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        
        score.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
@endsection