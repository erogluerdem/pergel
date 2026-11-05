@extends('layouts.master')

@section('title')
    Evrensel Arama Portalı - Pergel
@endsection

@section('topbar-title')
    Arama
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* DUAL THEME SYSTEM: DARK & LIGHT */
    :root, html[data-bs-theme="dark"] {
        --search-card-bg: rgba(21, 29, 44, 0.92);
        --search-card-border: rgba(255, 255, 255, 0.12);
        --search-text-primary: #FFFFFF;
        --search-text-secondary: #CBD5E1;
        --search-text-muted: #94A3B8;
        --search-feed-bg: rgba(15, 23, 42, 0.75);
        --search-chip-bg: rgba(255, 255, 255, 0.08);
        --search-input-bg: rgba(15, 23, 42, 0.95);
        --search-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    html[data-bs-theme="light"], body.theme-light {
        --search-card-bg: #FFFFFF;
        --search-card-border: #E2E8F0;
        --search-text-primary: #0F172A;
        --search-text-secondary: #334155;
        --search-text-muted: #475569;
        --search-feed-bg: #F8FAFC;
        --search-chip-bg: #E2E8F0;
        --search-input-bg: #FFFFFF;
        --search-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    }

    .search-hero-box {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(6, 182, 212, 0.08) 100%);
        border: 1px solid var(--search-card-border);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: var(--search-shadow);
        position: relative;
        overflow: hidden;
    }

    .search-hero-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366F1, #06B6D4, #10B981, #F59E0B, #6366F1);
        background-size: 300% 100%;
        animation: searchGlow 4s linear infinite;
    }

    @keyframes searchGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .search-input-group {
        background: var(--search-card-bg);
        border: 2px solid var(--search-card-border);
        border-radius: 50px;
        padding: 6px 12px 6px 24px;
        box-shadow: var(--search-shadow);
        transition: all 0.3s ease;
    }

    .search-input-group:focus-within {
        border-color: #6366F1;
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.25);
    }

    .search-input-group input {
        background: transparent !important;
        border: none !important;
        color: var(--search-text-primary) !important;
        font-size: 1.15rem;
        font-weight: 600;
        box-shadow: none !important;
    }

    .search-input-group input::placeholder {
        color: var(--search-text-muted) !important;
    }

    .search-chip {
        background: var(--search-chip-bg);
        border: 1px solid var(--search-card-border);
        color: var(--search-text-secondary);
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .search-chip:hover, .search-chip.active {
        background: #6366F1;
        color: #FFFFFF !important;
        border-color: #6366F1;
        transform: translateY(-2px);
    }

    .search-result-card {
        background: var(--search-card-bg);
        border: 1px solid var(--search-card-border);
        border-radius: 18px;
        padding: 20px;
        height: 100%;
        box-shadow: var(--search-shadow);
        transition: all 0.25s ease;
    }

    .search-result-card:hover {
        transform: translateY(-4px);
        border-color: rgba(99, 102, 241, 0.4);
        box-shadow: 0 15px 35px rgba(99, 102, 241, 0.15);
    }

    .search-icon-avatar {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .icon-project { background: rgba(99, 102, 241, 0.18); color: #6366F1; }
    .icon-task { background: rgba(16, 185, 129, 0.18); color: #10B981; }
    .icon-user { background: rgba(245, 158, 11, 0.18); color: #F59E0B; }
    .icon-doc { background: rgba(6, 182, 212, 0.18); color: #06B6D4; }

    mark {
        background: rgba(245, 158, 11, 0.35) !important;
        color: #F59E0B !important;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 4px;
    }

    /* High Contrast Badges */
    .search-badge {
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 800;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-4 py-3">

    <!-- HERO SEARCH BANNER -->
    <div class="search-hero-box">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-indigo bg-opacity-20 text-indigo px-3 py-1 rounded-pill fw-bold" style="color: #6366F1;">
                        <i class="fas fa-search me-1"></i> Evrensel İndeksleme & AI Arama Motoru
                    </span>
                    @if($totalCount > 0)
                        <span class="badge bg-success bg-opacity-20 text-success px-3 py-1 rounded-pill fw-bold">
                            {{ $totalCount }} Sonuç Bulundu
                        </span>
                    @endif
                </div>

                <h2 class="fw-bold mb-2">
                    Neyi <span class="text-indigo" style="color: #6366F1;">arıyorsunuz?</span>
                </h2>
                <p class="text-muted mb-4 font-size-14">
                    Projeler, hakedişler, görevler, şantiye personeli veya dokümanlar arasında akıllı arama yapın.
                </p>

                <!-- SEARCH FORM -->
                <form method="GET" action="{{ route('search.index') }}">
                    <div class="search-input-group d-flex align-items-center mb-3">
                        <i class="fas fa-search fs-5 text-muted me-2"></i>
                        <input type="text" name="q" value="{{ $query }}" placeholder="Örn: Göktürk, Hakediş #04, Baret, Pergel Tower..." autofocus>
                        <input type="hidden" name="category" value="{{ $category }}">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold me-1">
                            Ara <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </form>

                <!-- CATEGORY CHIPS -->
                <div class="d-flex align-items-center gap-2 flex-wrap mt-3">
                    <span class="text-muted font-size-12 fw-bold me-1">Kategori:</span>
                    <a href="{{ route('search.index', ['q' => $query, 'category' => 'all']) }}" class="search-chip {{ $category === 'all' ? 'active' : '' }}">
                        🔍 Tümü
                    </a>
                    <a href="{{ route('search.index', ['q' => $query, 'category' => 'projects']) }}" class="search-chip {{ $category === 'projects' ? 'active' : '' }}">
                        🏗️ Projeler
                    </a>
                    <a href="{{ route('search.index', ['q' => $query, 'category' => 'tasks']) }}" class="search-chip {{ $category === 'tasks' ? 'active' : '' }}">
                        📋 Görevler
                    </a>
                    <a href="{{ route('search.index', ['q' => $query, 'category' => 'users']) }}" class="search-chip {{ $category === 'users' ? 'active' : '' }}">
                        👥 Kullanıcılar
                    </a>
                    <a href="{{ route('search.index', ['q' => $query, 'category' => 'documents']) }}" class="search-chip {{ $category === 'documents' ? 'active' : '' }}">
                        📄 Dokümanlar
                    </a>
                </div>
            </div>

            <!-- QUICK SUGGESTIONS SIDEBOX -->
            <div class="col-lg-4 mt-4 mt-lg-0 text-lg-end">
                <div class="p-3 rounded-4" style="background: var(--search-feed-bg); border: 1px solid var(--search-card-border);">
                    <small class="text-muted fw-bold d-block mb-2 text-uppercase font-size-11">🔥 Hızlı Arama Etiketleri</small>
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="{{ route('search.index', ['q' => 'Göktürk']) }}" class="btn btn-xs btn-outline-secondary rounded-pill fw-bold">Göktürk</a>
                        <a href="{{ route('search.index', ['q' => 'Pergel Tower']) }}" class="btn btn-xs btn-outline-secondary rounded-pill fw-bold">Pergel Tower</a>
                        <a href="{{ route('search.index', ['q' => 'Hakediş']) }}" class="btn btn-xs btn-outline-secondary rounded-pill fw-bold">Hakediş</a>
                        <a href="{{ route('search.index', ['q' => 'İSG']) }}" class="btn btn-xs btn-outline-secondary rounded-pill fw-bold">İSG Denetimi</a>
                        <a href="{{ route('search.index', ['q' => 'Bursa OSB']) }}" class="btn btn-xs btn-outline-secondary rounded-pill fw-bold">Bursa OSB</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH RESULTS SECTION -->
    @if($query && strlen(trim($query)) < 2)
        <div class="alert alert-warning rounded-4 p-4 text-center">
            <i class="fas fa-exclamation-triangle fs-3 me-2"></i>
            Lütfen en az <strong>2 karakter</strong> girerek arama yapın.
        </div>
    @elseif($query && $totalCount === 0)
        <div class="text-center py-5 my-4">
            <div class="avatar-lg bg-indigo bg-opacity-10 text-indigo rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; color: #6366F1;">
                <i class="fas fa-search-minus fs-1"></i>
            </div>
            <h4 class="fw-bold mb-2">"{{ $query }}" için hiçbir sonuç bulunamadı</h4>
            <p class="text-muted font-size-14 max-w-md mx-auto">
                Farklı anahtar kelimeler deneyebilir veya kategorileri değiştirerek tekrar arayabilirsiniz.
            </p>
        </div>
    @elseif($query && $totalCount > 0)

        <!-- AI SEARCH INSIGHT BANNER -->
        <div class="p-3 rounded-4 mb-4 d-flex align-items-center gap-3" style="background: rgba(99, 102, 241, 0.12); border: 1px solid rgba(99, 102, 241, 0.3);">
            <div class="flex-shrink-0">
                <i class="fas fa-brain fs-3 text-indigo" style="color: #6366F1;"></i>
            </div>
            <div>
                <strong class="text-indigo" style="color: #6366F1;">AI Arama Özeti:</strong>
                <span class="font-size-13 text-muted ms-1">
                    "{{ $query }}" terimi için toplam <strong>{{ $totalCount }}</strong> eşleşme bulundu. Sonuçlar öncelik sırasına göre listelendi.
                </span>
            </div>
        </div>

        <!-- PROJECTS RESULTS -->
        @if(isset($results['projects']) && count($results['projects']) > 0)
            <div class="mb-5">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-building text-indigo" style="color: #6366F1;"></i> Projeler ({{ count($results['projects']) }})
                </h5>
                <div class="row g-3">
                    @foreach($results['projects'] as $project)
                        <div class="col-md-6 col-lg-4">
                            <div class="search-result-card">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="search-icon-avatar icon-project">
                                        <i class="fas fa-folder"></i>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <h6 class="fw-bold mb-1">
                                            <a href="{{ $project['url'] }}" class="text-reset text-decoration-none">
                                                {!! preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', $project['title']) !!}
                                            </a>
                                        </h6>
                                        <p class="text-muted font-size-12 mb-2">{{ $project['subtitle'] }}</p>
                                        @if(isset($project['description']) && $project['description'])
                                            <p class="text-muted font-size-13 mb-0 text-truncate">
                                                {!! Str::limit(preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', $project['description']), 90) !!}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- TASKS RESULTS -->
        @if(isset($results['tasks']) && count($results['tasks']) > 0)
            <div class="mb-5">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-tasks text-emerald" style="color: #10B981;"></i> Görevler ({{ count($results['tasks']) }})
                </h5>
                <div class="row g-3">
                    @foreach($results['tasks'] as $task)
                        <div class="col-md-6 col-lg-4">
                            <div class="search-result-card">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="search-icon-avatar icon-task">
                                        <i class="fas fa-check-square"></i>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <h6 class="fw-bold mb-1">
                                            <a href="{{ $task['url'] }}" class="text-reset text-decoration-none">
                                                {!! preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', $task['title']) !!}
                                            </a>
                                        </h6>
                                        <p class="text-muted font-size-12 mb-2">{{ $task['subtitle'] }}</p>
                                        @if(isset($task['description']) && $task['description'])
                                            <p class="text-muted font-size-13 mb-0 text-truncate">
                                                {!! Str::limit(preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', $task['description']), 90) !!}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- USERS RESULTS -->
        @if(isset($results['users']) && count($results['users']) > 0)
            <div class="mb-5">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-users text-warning"></i> Kullanıcılar & Personel ({{ count($results['users']) }})
                </h5>
                <div class="row g-3">
                    @foreach($results['users'] as $user)
                        <div class="col-md-6 col-lg-4">
                            <div class="search-result-card">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="search-icon-avatar icon-user">
                                        <i class="fas fa-user font-size-18"></i>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <h6 class="fw-bold mb-1">
                                            <a href="{{ $user['url'] }}" class="text-reset text-decoration-none">
                                                {!! preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', $user['title']) !!}
                                            </a>
                                        </h6>
                                        <p class="text-muted font-size-12 mb-1">{{ $user['subtitle'] }}</p>
                                        @if(isset($user['description']) && $user['description'])
                                            <small class="text-indigo fw-bold" style="color: #6366F1;">{{ $user['description'] }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- DOCUMENTS RESULTS -->
        @if(isset($results['documents']) && count($results['documents']) > 0)
            <div class="mb-5">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-file-alt text-cyan" style="color: #06B6D4;"></i> Dokümanlar ({{ count($results['documents']) }})
                </h5>
                <div class="row g-3">
                    @foreach($results['documents'] as $document)
                        <div class="col-md-6 col-lg-4">
                            <div class="search-result-card">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="search-icon-avatar icon-doc">
                                        <i class="fas fa-file"></i>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <h6 class="fw-bold mb-1">
                                            <a href="{{ $document['url'] }}" class="text-reset text-decoration-none">
                                                {!! preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', $document['title']) !!}
                                            </a>
                                        </h6>
                                        <p class="text-muted font-size-12 mb-2">{{ $document['subtitle'] }}</p>
                                        @if(isset($document['description']) && $document['description'])
                                            <p class="text-muted font-size-13 mb-0 text-truncate">
                                                {!! Str::limit(preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', $document['description']), 90) !!}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @else
        <!-- DEFAULT INITIAL SEARCH STATE -->
        <div class="text-center py-5 my-3">
            <div class="avatar-lg bg-indigo bg-opacity-10 text-indigo rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; color: #6366F1;">
                <i class="fas fa-search fs-1"></i>
            </div>
            <h4 class="fw-bold mb-2">Pergel Arama Portalı</h4>
            <p class="text-muted font-size-14 max-w-md mx-auto">
                Yukarıdaki arama çubuğunu veya hazır etiketleri kullanarak arama yapmaya başlayın.
            </p>
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 800, once: true });
        }
    });
</script>
@endsection