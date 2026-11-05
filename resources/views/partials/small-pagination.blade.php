@php
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

// If a Collection was passed accidentally, convert it to a LengthAwarePaginator
if ($paginator instanceof Collection) {
    $page = (int) request()->get('page', 1);
    $perPage = 20; // sensible default; matches controller paginate
    $items = $paginator->forPage($page, $perPage)->values();
    $paginator = new LengthAwarePaginator($items, $paginator->count(), $perPage, $page, [
        'path' => request()->url(),
        'query' => request()->query(),
    ]);
}
@endphp

@if(is_object($paginator) && method_exists($paginator, 'elements') && $paginator->hasPages())
<style>
/* Small, robust pagination styles scoped to this partial */
.small-pagination { text-align:center; margin-top:0.5rem; }
.small-pagination ul { display:inline-flex; gap:0.35rem; padding:0; margin:0; list-style:none; align-items:center; }
.small-pagination .page-item { display:inline-block; }
.small-pagination .page-link { display:inline-flex; align-items:center; justify-content:center; padding:0.25rem 0.5rem; min-width:32px; height:32px; font-size:0.85rem; line-height:1; border-radius:6px; border:1px solid rgba(0,0,0,0.06); background:transparent; }
.small-pagination .page-item.disabled .page-link { opacity:0.6; pointer-events:none; }
.small-pagination .page-item.active .page-link { background:#0d6efd; color:#fff; border-color:#0d6efd; }
.small-pagination svg { width:14px !important; height:14px !important; }
</style>
<nav class="small-pagination" role="navigation" aria-label="Pagination Navigation">
    <ul>
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">&lsaquo;</span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">&lsaquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @if(method_exists($paginator, 'lastPage'))
            @php
                $last = $paginator->lastPage();
                $current = $paginator->currentPage();
            @endphp
            @for ($page = 1; $page <= $last; $page++)
                @if ($page == $current)
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                @endif
            @endfor
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">&rsaquo;</a></li>
        @else
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">&rsaquo;</span></li>
        @endif
    </ul>
</nav>
@endif

