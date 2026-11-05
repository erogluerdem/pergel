<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb m-0">
        @if(!empty($li_1))
            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $li_1 }}</a></li>
        @endif
        @if(!empty($li_2))
            <li class="breadcrumb-item">{{ $li_2 }}</li>
        @endif
        <li class="breadcrumb-item active">{{ $title ?? '' }}</li>
    </ol>
</nav>
