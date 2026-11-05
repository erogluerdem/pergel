@props(['location' => null])

@php
    $loc = $location;
@endphp

@if(is_array($loc) || is_object($loc))
    @php
        $lat = null; $lng = null;
        if(is_array($loc)) { $lat = $loc['lat'] ?? null; $lng = $loc['lng'] ?? null; }
        else { $lat = $loc->lat ?? null; $lng = $loc->lng ?? null; }
    @endphp

    @if($lat !== null || $lng !== null)
        <span>{{ $lat ?? '-' }}, {{ $lng ?? '-' }}</span>
    @else
        <span>{{ json_encode((array) $loc, JSON_UNESCAPED_UNICODE) }}</span>
    @endif
@else
    <span>{{ $loc ?? '-' }}</span>
@endif
