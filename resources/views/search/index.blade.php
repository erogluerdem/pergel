@extends('layouts.master')

@section('title')
    Evrensel Arama Portalı - Pergel
@endsection

@section('topbar-title')
    Arama
@endsection

@section('css')
<style>
    .sx { --sx-acc: var(--pt-primary, #d9992e); --sx-acc-rgb: var(--pt-primary-rgb, 217,153,46); }
    .sx-hero { background: var(--pt-hero-bg); border-radius: 16px; padding: 30px 32px; margin-bottom: 24px; position: relative; overflow: hidden; color: #fff; }
    .sx-hero::after { content:''; position:absolute; right:-30px; bottom:-30px; width:260px; height:260px; opacity:.10; pointer-events:none;
        background: currentColor; -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64' fill='none' stroke='black' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='27' cy='27' r='16'/%3E%3Cpath d='M39 39l17 17'/%3E%3Cpath d='M20 27h14M27 20v14'/%3E%3C/svg%3E") center/contain no-repeat; mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64' fill='none' stroke='black' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='27' cy='27' r='16'/%3E%3Cpath d='M39 39l17 17'/%3E%3Cpath d='M20 27h14M27 20v14'/%3E%3C/svg%3E") center/contain no-repeat; }
    .sx-hero > * { position: relative; z-index: 1; }
    .sx-eyebrow { display:inline-flex; align-items:center; gap:6px; font-size:.75rem; font-weight:600; letter-spacing:.4px; text-transform:uppercase;
        background: rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.16); color:#fff; padding:5px 12px; border-radius:30px; }
    .sx-count { display:inline-flex; align-items:center; gap:6px; font-size:.75rem; font-weight:600; background: rgba(var(--sx-acc-rgb), .22); color:#fff; border:1px solid rgba(var(--sx-acc-rgb), .5); padding:5px 12px; border-radius:30px; }
    .sx-hero h2 { color:#fff !important; font-weight:700; font-size:1.65rem; margin: 14px 0 6px; }
    .sx-hero h2 span { color: var(--sh-acc-text, var(--sx-acc)); }
    .sx-hero p { color: rgba(255,255,255,.75) !important; margin-bottom: 20px; font-size:.9rem; }

    .sx-form { display:flex; align-items:center; gap:10px; background: var(--pt-card-bg); border:1px solid var(--pt-card-border); border-radius: 14px; padding: 6px 6px 6px 16px; max-width: 760px; transition: box-shadow .15s, border-color .15s; }
    .sx-form:focus-within { border-color: var(--sx-acc); box-shadow: 0 0 0 3px rgba(var(--sx-acc-rgb), .25); }
    .sx-form > i { font-size: 1.25rem; color: var(--pt-text-muted); }
    .sx-form input[type=text] { flex:1; min-width:0; border:0 !important; background: transparent !important; box-shadow:none !important; color: var(--pt-heading-color) !important; font-size: 1rem; padding: 10px 4px; outline:0; }
    .sx-form input::placeholder { color: var(--pt-input-placeholder, var(--pt-text-muted)) !important; }
    .sx-form button { border-radius: 10px; padding: 10px 22px; font-weight:600; white-space:nowrap; }

    .sx-chips { display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin-top: 18px; }
    .sx-chips .lbl { font-size:.75rem; color: rgba(255,255,255,.65); font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-right:4px; }
    .sx-chip { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:30px; font-size:.82rem; font-weight:600; text-decoration:none;
        background: rgba(255,255,255,.10); color:#fff; border:1px solid rgba(255,255,255,.16); transition: background .15s, border-color .15s; }
    .sx-chip:hover { background: rgba(255,255,255,.18); color:#fff; }
    .sx-chip.active { background: var(--sx-acc); border-color: var(--sx-acc); color: var(--sh-on-acc, #1f2937); }
    .sx-chip.active:hover { color: var(--sh-on-acc, #1f2937); }

    .sx-tags { background: rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.14); border-radius: 14px; padding: 16px; }
    .sx-tags small { display:block; font-size:.7rem; font-weight:700; letter-spacing:.6px; text-transform:uppercase; color: rgba(255,255,255,.65); margin-bottom:10px; }
    .sx-tag { display:inline-block; padding:5px 12px; margin:0 6px 6px 0; border-radius: 8px; font-size:.8rem; font-weight:600; text-decoration:none;
        background: rgba(255,255,255,.10); color:#fff; border:1px solid rgba(255,255,255,.14); }
    .sx-tag:hover { border-color: var(--sx-acc); color:#fff; background: rgba(var(--sx-acc-rgb), .25); }

    .sx-insight { display:flex; align-items:center; gap:14px; background: var(--pt-card-bg); border:1px solid var(--pt-card-border); border-left: 4px solid var(--sx-acc); border-radius: 12px; padding: 14px 18px; margin-bottom: 26px; }
    .sx-insight .ic { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; background: rgba(var(--sx-acc-rgb), .16); color: var(--pt-link-color, var(--sx-acc)); font-size:1.25rem; flex-shrink:0; }
    .sx-insight b { color: var(--pt-heading-color); }
    .sx-insight span { color: var(--pt-text-muted); font-size:.875rem; }

    .sx-sec { display:flex; align-items:center; gap:10px; margin: 0 0 14px; }
    .sx-sec h5 { margin:0; font-size:1rem; font-weight:700; color: var(--pt-heading-color); }
    .sx-sec .n { font-size:.75rem; font-weight:700; padding:2px 10px; border-radius:20px; background: rgba(var(--sx-acc-rgb), .16); color: var(--pt-link-color, var(--sx-acc)); }
    .sx-sec .ic { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:1.05rem; }

    .sx-card { display:flex; gap:14px; background: var(--pt-card-bg); border:1px solid var(--pt-card-border); border-radius: 14px; padding: 16px; height:100%; text-decoration:none; color: inherit; transition: border-color .15s, box-shadow .15s; box-shadow: var(--pt-card-shadow, none); }
    .sx-card:hover { border-color: rgba(var(--sx-acc-rgb), .7); box-shadow: 0 6px 18px rgba(0,0,0,.08); }
    .sx-av { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
    .sx-av.p { background: rgba(var(--sx-acc-rgb), .16); color: var(--pt-link-color, var(--sx-acc)); }
    .sx-av.t { background: rgba(42,118,86,.16); color: #2a7656; }
    .sx-av.u { background: rgba(40,114,143,.16); color: #28728f; }
    .sx-av.d { background: rgba(91,103,120,.18); color: var(--pt-text-muted); }
    html[data-dark="true"] .sx-av.t { color:#6fcfa6; } html[data-dark="true"] .sx-av.u { color:#7cc3e0; }
    .sx-card h6 { margin:0 0 3px; font-size:.95rem; font-weight:600; color: var(--pt-heading-color); }
    .sx-card .sub { color: var(--pt-text-muted); font-size:.8rem; margin:0 0 6px; }
    .sx-card .desc { color: var(--pt-text-muted); font-size:.82rem; margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .sx-card .meta { color: var(--pt-link-color, var(--sx-acc)); font-size:.78rem; font-weight:600; }
    .sx-card .go { margin-left:auto; align-self:center; color: var(--pt-text-subtle, var(--pt-text-muted)); font-size:1.1rem; }
    .sx-card:hover .go { color: var(--sx-acc); }
    .sx mark { background: rgba(var(--sx-acc-rgb), .30) !important; color: inherit !important; font-weight:700; padding:0 3px; border-radius:4px; }

    .sx-empty { text-align:center; padding: 48px 16px; background: var(--pt-card-bg); border:1px dashed var(--pt-card-border); border-radius:16px; }
    .sx-empty .big { width:76px; height:76px; border-radius:50%; margin:0 auto 14px; display:flex; align-items:center; justify-content:center; font-size:2rem; background: rgba(var(--sx-acc-rgb), .14); color: var(--pt-link-color, var(--sx-acc)); }
    .sx-empty h4 { color: var(--pt-heading-color); font-weight:700; font-size:1.1rem; }
    .sx-empty p { color: var(--pt-text-muted); max-width: 440px; margin: 6px auto 0; font-size:.9rem; }
    @media (max-width: 575px) { .sx-hero { padding: 22px 18px; } .sx-form button span { display:none; } }
</style>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-4 py-3 sx">

    <div class="sx-hero">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="sx-eyebrow"><i class="ri-search-eye-line"></i> Evrensel Arama</span>
                    @if($totalCount > 0)
                        <span class="sx-count"><i class="ri-check-double-line"></i> {{ $totalCount }} sonuç bulundu</span>
                    @endif
                </div>
                <h2>Neyi <span>arıyorsunuz?</span></h2>
                <p>Projeler, görevler, şantiye personeli veya dokümanlar arasında tek yerden arama yapın.</p>

                <form method="GET" action="{{ route('search.index') }}">
                    <div class="sx-form">
                        <i class="ri-search-line"></i>
                        <input type="text" name="q" value="{{ $query }}" placeholder="Örn: Göktürk, Hakediş #04, Baret, Pergel Tower..." autofocus>
                        <input type="hidden" name="category" value="{{ $category }}">
                        <button type="submit" class="btn btn-primary"><span>Ara</span> <i class="ri-arrow-right-line"></i></button>
                    </div>
                </form>

                <div class="sx-chips">
                    <span class="lbl">Kategori</span>
                    <a href="{{ route('search.index', ['q' => $query, 'category' => 'all']) }}" class="sx-chip {{ $category === 'all' ? 'active' : '' }}"><i class="ri-apps-2-line"></i> Tümü</a>
                    <a href="{{ route('search.index', ['q' => $query, 'category' => 'projects']) }}" class="sx-chip {{ $category === 'projects' ? 'active' : '' }}"><i class="ri-building-2-line"></i> Projeler</a>
                    <a href="{{ route('search.index', ['q' => $query, 'category' => 'tasks']) }}" class="sx-chip {{ $category === 'tasks' ? 'active' : '' }}"><i class="ri-task-line"></i> Görevler</a>
                    <a href="{{ route('search.index', ['q' => $query, 'category' => 'users']) }}" class="sx-chip {{ $category === 'users' ? 'active' : '' }}"><i class="ri-team-line"></i> Kullanıcılar</a>
                    <a href="{{ route('search.index', ['q' => $query, 'category' => 'documents']) }}" class="sx-chip {{ $category === 'documents' ? 'active' : '' }}"><i class="ri-file-text-line"></i> Dokümanlar</a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sx-tags">
                    <small>Hızlı arama etiketleri</small>
                    <a href="{{ route('search.index', ['q' => 'Göktürk']) }}" class="sx-tag">Göktürk</a>
                    <a href="{{ route('search.index', ['q' => 'Pergel Tower']) }}" class="sx-tag">Pergel Tower</a>
                    <a href="{{ route('search.index', ['q' => 'Hakediş']) }}" class="sx-tag">Hakediş</a>
                    <a href="{{ route('search.index', ['q' => 'İSG']) }}" class="sx-tag">İSG Denetimi</a>
                    <a href="{{ route('search.index', ['q' => 'Bursa OSB']) }}" class="sx-tag">Bursa OSB</a>
                </div>
            </div>
        </div>
    </div>

    @if($query && strlen(trim($query)) < 2)
        <div class="sx-empty">
            <div class="big"><i class="ri-error-warning-line"></i></div>
            <h4>Arama çok kısa</h4>
            <p>Lütfen en az <strong>2 karakter</strong> girerek arama yapın.</p>
        </div>
    @elseif($query && $totalCount === 0)
        <div class="sx-empty">
            <div class="big"><i class="ri-search-eye-line"></i></div>
            <h4>"{{ $query }}" için sonuç bulunamadı</h4>
            <p>Farklı anahtar kelimeler deneyebilir veya kategoriyi değiştirerek tekrar arayabilirsiniz.</p>
        </div>
    @elseif($query && $totalCount > 0)

        <div class="sx-insight">
            <div class="ic"><i class="ri-sparkling-2-line"></i></div>
            <div>
                <b>Arama özeti:</b>
                <span>"{{ $query }}" için toplam <b>{{ $totalCount }}</b> eşleşme bulundu; sonuçlar öncelik sırasına göre listelendi.</span>
            </div>
        </div>

        @if(isset($results['projects']) && count($results['projects']) > 0)
            <div class="mb-5">
                <div class="sx-sec">
                    <span class="ic sx-av p" style="width:34px;height:34px;font-size:1.05rem;border-radius:9px"><i class="ri-building-2-line"></i></span>
                    <h5>Projeler</h5><span class="n">{{ count($results['projects']) }}</span>
                </div>
                <div class="row g-3">
                    @foreach($results['projects'] as $item)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ $item['url'] }}" class="sx-card">
                                <div class="sx-av p"><i class="ri-folder-3-line"></i></div>
                                <div class="min-w-0" style="min-width:0">
                                    <h6>{!! preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', e($item['title'])) !!}</h6>
                                    <p class="sub">{{ $item['subtitle'] ?? '' }}</p>
                                    @if(!empty($item['description']))
                                        <p class="desc">{!! Str::limit(preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', e($item['description'])), 90) !!}</p>
                                    @endif
                                </div>
                                <i class="ri-arrow-right-s-line go"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(isset($results['tasks']) && count($results['tasks']) > 0)
            <div class="mb-5">
                <div class="sx-sec">
                    <span class="ic sx-av t" style="width:34px;height:34px;font-size:1.05rem;border-radius:9px"><i class="ri-task-line"></i></span>
                    <h5>Görevler</h5><span class="n">{{ count($results['tasks']) }}</span>
                </div>
                <div class="row g-3">
                    @foreach($results['tasks'] as $item)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ $item['url'] }}" class="sx-card">
                                <div class="sx-av t"><i class="ri-checkbox-circle-line"></i></div>
                                <div class="min-w-0" style="min-width:0">
                                    <h6>{!! preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', e($item['title'])) !!}</h6>
                                    <p class="sub">{{ $item['subtitle'] ?? '' }}</p>
                                    @if(!empty($item['description']))
                                        <p class="desc">{!! Str::limit(preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', e($item['description'])), 90) !!}</p>
                                    @endif
                                </div>
                                <i class="ri-arrow-right-s-line go"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(isset($results['users']) && count($results['users']) > 0)
            <div class="mb-5">
                <div class="sx-sec">
                    <span class="ic sx-av u" style="width:34px;height:34px;font-size:1.05rem;border-radius:9px"><i class="ri-team-line"></i></span>
                    <h5>Kullanıcılar &amp; Personel</h5><span class="n">{{ count($results['users']) }}</span>
                </div>
                <div class="row g-3">
                    @foreach($results['users'] as $item)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ $item['url'] }}" class="sx-card">
                                <div class="sx-av u"><i class="ri-user-3-line"></i></div>
                                <div class="min-w-0" style="min-width:0">
                                    <h6>{!! preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', e($item['title'])) !!}</h6>
                                    <p class="sub">{{ $item['subtitle'] ?? '' }}</p>
                                    @if(!empty($item['description']))
                                        <span class="meta">{{ $item['description'] }}</span>
                                    @endif
                                </div>
                                <i class="ri-arrow-right-s-line go"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(isset($results['documents']) && count($results['documents']) > 0)
            <div class="mb-5">
                <div class="sx-sec">
                    <span class="ic sx-av d" style="width:34px;height:34px;font-size:1.05rem;border-radius:9px"><i class="ri-file-text-line"></i></span>
                    <h5>Dokümanlar</h5><span class="n">{{ count($results['documents']) }}</span>
                </div>
                <div class="row g-3">
                    @foreach($results['documents'] as $item)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ $item['url'] }}" class="sx-card">
                                <div class="sx-av d"><i class="ri-file-3-line"></i></div>
                                <div class="min-w-0" style="min-width:0">
                                    <h6>{!! preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', e($item['title'])) !!}</h6>
                                    <p class="sub">{{ $item['subtitle'] ?? '' }}</p>
                                    @if(!empty($item['description']))
                                        <p class="desc">{!! Str::limit(preg_replace('/('.preg_quote($query, '/').')/i', '<mark>$1</mark>', e($item['description'])), 90) !!}</p>
                                    @endif
                                </div>
                                <i class="ri-arrow-right-s-line go"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @else
        <div class="sx-empty">
            <div class="big"><i class="ri-search-2-line"></i></div>
            <h4>Pergel Arama Portalı</h4>
            <p>Yukarıdaki arama çubuğunu veya hazır etiketleri kullanarak aramaya başlayın.</p>
        </div>
    @endif

</div>
@endsection
